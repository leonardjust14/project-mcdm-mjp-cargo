<div class="form-grid">
    <div class="input">
        <label for="name">Truck Name</label>
        <input id="name" name="name" value="{{ old('name', $alternative->name ?? '') }}" required>
    </div>
    <div class="input">
        <label for="code">Code</label>
        <input id="code" name="code" value="{{ old('code', $alternative->code ?? '') }}">
    </div>
    <div class="input">
        <label for="is_active">Status</label>
        @php
            $isActive = old('is_active', $alternative->is_active ?? true);
        @endphp
        <select id="is_active" name="is_active">
            <option value="1" {{ (string) $isActive === '1' || $isActive === true ? 'selected' : '' }}>Active</option>
            <option value="0" {{ (string) $isActive === '0' || $isActive === false ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
</div>

<div class="section-title">
    <h2>Criteria Values</h2>
</div>

<div class="form-grid">
    @foreach ($criteria as $criterion)
        @php
            $value = $alternative->values->firstWhere('criteria_id', $criterion->id) ?? null;
        @endphp
        <div class="input">
            <label for="criteria_{{ $criterion->id }}">{{ $criterion->name }} ({{ $criterion->unit ?? '-' }})</label>
            <input
                id="criteria_{{ $criterion->id }}"
                name="values[{{ $criterion->id }}]"
                value="{{ old('values.' . $criterion->id, $value->value ?? '') }}"
                required
            >
        </div>
    @endforeach
</div>
