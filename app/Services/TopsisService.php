<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use Illuminate\Support\Collection;

class TopsisService
{
    /**
     * @param Collection<int, Alternative> $alternatives
     * @param Collection<int, Criteria> $criteria
     * @return array<string, mixed>
     */
    public function calculate(Collection $alternatives, Collection $criteria): array
    {
        $criteria = $criteria->values();
        $alternatives = $alternatives->values();

        $decisionMatrix = [];
        foreach ($alternatives as $alternative) {
            $decisionMatrix[$alternative->id] = [];
            foreach ($criteria as $criterion) {
                $valueRow = $alternative->values->firstWhere('criteria_id', $criterion->id);
                $decisionMatrix[$alternative->id][$criterion->id] = $valueRow ? (float) $valueRow->value : 0.0;
            }
        }

        $normalizedMatrix = $this->normalizeMatrix($decisionMatrix, $criteria);
        $weightedMatrix = $this->applyWeights($normalizedMatrix, $criteria);
        [$idealPositive, $idealNegative] = $this->idealSolutions($weightedMatrix, $criteria);
        $distances = $this->distances($weightedMatrix, $idealPositive, $idealNegative);
        $scores = $this->scores($distances);
        $ranking = $this->rank($alternatives, $scores, $distances);

        return [
            'criteria' => $criteria,
            'alternatives' => $alternatives,
            'decisionMatrix' => $decisionMatrix,
            'normalizedMatrix' => $normalizedMatrix,
            'weightedMatrix' => $weightedMatrix,
            'idealPositive' => $idealPositive,
            'idealNegative' => $idealNegative,
            'distances' => $distances,
            'scores' => $scores,
            'ranking' => $ranking,
        ];
    }

    /**
     * @param array<int, array<int, float>> $matrix
     * @param Collection<int, Criteria> $criteria
     * @return array<int, array<int, float>>
     */
    private function normalizeMatrix(array $matrix, Collection $criteria): array
    {
        $divisors = [];
        foreach ($criteria as $criterion) {
            $sumSquares = 0.0;
            foreach ($matrix as $row) {
                $sumSquares += ($row[$criterion->id] ?? 0.0) ** 2;
            }
            $divisors[$criterion->id] = $sumSquares > 0 ? sqrt($sumSquares) : 1.0;
        }

        $normalized = [];
        foreach ($matrix as $altId => $row) {
            foreach ($criteria as $criterion) {
                $normalized[$altId][$criterion->id] = ($row[$criterion->id] ?? 0.0) / $divisors[$criterion->id];
            }
        }

        return $normalized;
    }

    /**
     * @param array<int, array<int, float>> $matrix
     * @param Collection<int, Criteria> $criteria
     * @return array<int, array<int, float>>
     */
    private function applyWeights(array $matrix, Collection $criteria): array
    {
        $weighted = [];
        foreach ($matrix as $altId => $row) {
            foreach ($criteria as $criterion) {
                $weighted[$altId][$criterion->id] = ($row[$criterion->id] ?? 0.0) * (float) $criterion->weight;
            }
        }

        return $weighted;
    }

    /**
     * @param array<int, array<int, float>> $matrix
     * @param Collection<int, Criteria> $criteria
     * @return array{0: array<int, float>, 1: array<int, float>}
     */
    private function idealSolutions(array $matrix, Collection $criteria): array
    {
        $idealPositive = [];
        $idealNegative = [];

        foreach ($criteria as $criterion) {
            $column = [];
            foreach ($matrix as $row) {
                $column[] = $row[$criterion->id] ?? 0.0;
            }

            $max = empty($column) ? 0.0 : max($column);
            $min = empty($column) ? 0.0 : min($column);

            if ($criterion->type === 'benefit') {
                $idealPositive[$criterion->id] = $max;
                $idealNegative[$criterion->id] = $min;
            } else {
                $idealPositive[$criterion->id] = $min;
                $idealNegative[$criterion->id] = $max;
            }
        }

        return [$idealPositive, $idealNegative];
    }

    /**
     * @param array<int, array<int, float>> $matrix
     * @param array<int, float> $idealPositive
     * @param array<int, float> $idealNegative
     * @return array<int, array<string, float>>
     */
    private function distances(array $matrix, array $idealPositive, array $idealNegative): array
    {
        $distances = [];
        foreach ($matrix as $altId => $row) {
            $sumPositive = 0.0;
            $sumNegative = 0.0;

            foreach ($row as $criterionId => $value) {
                $sumPositive += ($value - ($idealPositive[$criterionId] ?? 0.0)) ** 2;
                $sumNegative += ($value - ($idealNegative[$criterionId] ?? 0.0)) ** 2;
            }

            $distances[$altId] = [
                'positive' => sqrt($sumPositive),
                'negative' => sqrt($sumNegative),
            ];
        }

        return $distances;
    }

    /**
     * @param array<int, array<string, float>> $distances
     * @return array<int, float>
     */
    private function scores(array $distances): array
    {
        $scores = [];
        foreach ($distances as $altId => $distance) {
            $denom = $distance['positive'] + $distance['negative'];
            $scores[$altId] = $denom > 0 ? $distance['negative'] / $denom : 0.0;
        }

        return $scores;
    }

    /**
     * @param Collection<int, Alternative> $alternatives
     * @param array<int, float> $scores
     * @param array<int, array<string, float>> $distances
     * @return array<int, array<string, mixed>>
     */
    private function rank(Collection $alternatives, array $scores, array $distances): array
    {
        $rows = [];
        foreach ($alternatives as $alternative) {
            $rows[] = [
                'alternative' => $alternative,
                'score' => $scores[$alternative->id] ?? 0.0,
                'd_positive' => $distances[$alternative->id]['positive'] ?? 0.0,
                'd_negative' => $distances[$alternative->id]['negative'] ?? 0.0,
            ];
        }

        usort($rows, function (array $a, array $b): int {
            return $b['score'] <=> $a['score'];
        });

        foreach ($rows as $index => $row) {
            $rows[$index]['rank'] = $index + 1;
        }

        return $rows;
    }
}
