<template>
  <div class="filters">
    <div class="filter-group">
      <label class="filter-label">Status</label>
      <select
        class="filter-select"
        :value="status"
        @change="$emit('update:status', ($event.target).value)"
      >
        <option value="">All</option>
        <option value="active">Active</option>
        <option value="canceled">Canceled</option>
        <option value="past_due">Past Due</option>
        <option value="trialing">Trialing</option>
        <option value="unpaid">Unpaid</option>
      </select>
    </div>

    <div class="filter-group">
      <label class="filter-label">Customer</label>
      <select
        class="filter-select"
        :value="customerId"
        @change="$emit('update:customerId', ($event.target).value)"
      >
        <option value="">All</option>
        <option
          v-for="c in customers"
          :key="c.id"
          :value="c.id"
        >{{ c.name }}</option>
      </select>
    </div>

    <div class="filter-group filter-group--grow">
      <label class="filter-label">Search</label>
      <input
        class="filter-input"
        type="text"
        placeholder="Search by customer name, email, or ID..."
        :value="search"
        @input="$emit('update:search', ($event.target).value)"
      />
    </div>
  </div>
</template>

<script setup>
defineProps({
  status: String,
  customerId: [String, Number],
  search: String,
  customers: Array,
})

defineEmits(['update:status', 'update:customerId', 'update:search'])
</script>

<style scoped>
.filters {
  display: flex;
  gap: 1rem;
  flex-wrap: wrap;
}

.filter-group {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.filter-group--grow {
  flex: 1;
  min-width: 200px;
}

.filter-label {
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #6b7280;
}

.filter-select,
.filter-input {
  padding: 0.5rem 0.75rem;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-size: 0.875rem;
  background: #fff;
  outline: none;
  transition: border-color 0.15s;
}

.filter-select:focus,
.filter-input:focus {
  border-color: #1a1a2e;
}

.filter-input {
  width: 100%;
}
</style>
