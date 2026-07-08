<template>
  <div class="table-container">
    <div v-if="loading" class="loading">Loading...</div>
    <table v-else class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Plan</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Period Start</th>
          <th>Period End</th>
        </tr>
      </thead>
      <tbody>
        <tr v-if="subscriptions.length === 0">
          <td colspan="7" class="empty">No subscriptions found.</td>
        </tr>
        <tr v-for="sub in subscriptions" :key="sub.id">
          <td class="cell-id">{{ sub.id }}</td>
          <td>
            <div class="customer-cell">
              <span class="customer-name">{{ sub.customer?.name }}</span>
              <span class="customer-email">{{ sub.customer?.email }}</span>
            </div>
          </td>
          <td>{{ sub.product?.name }}</td>
          <td class="cell-amount">{{ formatAmount(sub.product?.amount, sub.product?.currency) }}</td>
          <td>
            <span :class="['status-badge', 'status-badge--' + sub.status]">
              {{ sub.status }}
            </span>
          </td>
          <td class="cell-date">{{ formatDate(sub.current_period_start) }}</td>
          <td class="cell-date">{{ formatDate(sub.current_period_end) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
defineProps({
  subscriptions: Array,
  loading: Boolean,
})

function formatDate(dateStr) {
  if (!dateStr) return '—'
  const d = new Date(dateStr)
  return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })
}

function formatAmount(amount, currency) {
  if (amount == null) return '—'
  const symbol = currency === 'usd' ? '$' : (currency || '').toUpperCase() + ' '
  return symbol + (amount / 100).toFixed(2)
}
</script>

<style scoped>
.table-container {
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.loading {
  padding: 2rem;
  text-align: center;
  color: #6b7280;
}

.table {
  width: 100%;
  border-collapse: collapse;
}

.table th {
  background: #f9fafb;
  padding: 0.75rem 1rem;
  text-align: left;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #6b7280;
  border-bottom: 2px solid #e5e7eb;
}

.table td {
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  border-bottom: 1px solid #f3f4f6;
  vertical-align: middle;
}

.table tr:last-child td {
  border-bottom: none;
}

.table tr:hover td {
  background: #f9fafb;
}

.empty {
  text-align: center;
  color: #9ca3af;
  padding: 2rem !important;
}

.cell-id {
  font-weight: 600;
  color: #374151;
}

.cell-amount {
  font-variant-numeric: tabular-nums;
  font-weight: 600;
}

.cell-date {
  font-size: 0.8125rem;
  color: #6b7280;
  white-space: nowrap;
}

.customer-cell {
  display: flex;
  flex-direction: column;
}

.customer-name {
  font-weight: 600;
  color: #374151;
}

.customer-email {
  font-size: 0.75rem;
  color: #9ca3af;
}

.status-badge {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  padding: 0.125rem 0.5rem;
  border-radius: 4px;
  white-space: nowrap;
}

.status-badge--active {
  background: #d1fae5;
  color: #065f46;
}

.status-badge--canceled {
  background: #fee2e2;
  color: #991b1b;
}

.status-badge--past_due {
  background: #fef3c7;
  color: #92400e;
}

.status-badge--trialing {
  background: #dbeafe;
  color: #1e40af;
}

.status-badge--unpaid {
  background: #f3e8ff;
  color: #6b21a8;
}
</style>
