<template>
  <div class="dashboard">
    <SyncPanel
      :syncing="syncing"
      :last-sync="lastSync"
      @sync="handleSync"
    />

    <SubscriptionFilters
      v-model:status="filters.status"
      v-model:customer-id="filters.customerId"
      v-model:search="filters.search"
      :customers="customers"
    />

    <div class="stats">
      <div class="stat">
        <span class="stat-label">Total</span>
        <span class="stat-value">{{ subscriptions.length }}</span>
      </div>
      <div class="stat stat--active">
        <span class="stat-label">Active</span>
        <span class="stat-value">{{ countByStatus('active') }}</span>
      </div>
      <div class="stat stat--canceled">
        <span class="stat-label">Canceled</span>
        <span class="stat-value">{{ countByStatus('canceled') }}</span>
      </div>
      <div class="stat stat--past_due">
        <span class="stat-label">Past Due</span>
        <span class="stat-value">{{ countByStatus('past_due') }}</span>
      </div>
      <div class="stat stat--trialing">
        <span class="stat-label">Trialing</span>
        <span class="stat-value">{{ countByStatus('trialing') }}</span>
      </div>
      <div class="stat stat--unpaid">
        <span class="stat-label">Unpaid</span>
        <span class="stat-value">{{ countByStatus('unpaid') }}</span>
      </div>
    </div>

    <SubscriptionTable
      :subscriptions="filteredSubscriptions"
      :loading="loading"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { fetchSubscriptions, fetchCustomers, triggerSync, fetchLastSync } from '../api'
import SyncPanel from './SyncPanel.vue'
import SubscriptionFilters from './SubscriptionFilters.vue'
import SubscriptionTable from './SubscriptionTable.vue'

const subscriptions = ref([])
const customers = ref([])
const lastSync = ref(null)
const loading = ref(false)
const syncing = ref(false)

const filters = ref({
  status: '',
  customerId: '',
  search: '',
})

const filteredSubscriptions = computed(() => {
  return subscriptions.value.filter(sub => {
    if (filters.value.status && sub.status !== filters.value.status) return false
    if (filters.value.customerId && sub.customer?.id !== Number(filters.value.customerId)) return false
    if (filters.value.search) {
      const q = filters.value.search.toLowerCase()
      const name = sub.customer?.name?.toLowerCase() || ''
      const email = sub.customer?.email?.toLowerCase() || ''
      const id = String(sub.id)
      if (!name.includes(q) && !email.includes(q) && !id.includes(q)) return false
    }
    return true
  })
})

function countByStatus(status) {
  return subscriptions.value.filter(s => s.status === status).length
}

async function loadData() {
  loading.value = true
  try {
    const [subs, custs, sync] = await Promise.all([
      fetchSubscriptions(),
      fetchCustomers(),
      fetchLastSync(),
    ])
    subscriptions.value = subs
    customers.value = custs
    lastSync.value = sync
  } catch (e) {
    console.error('Failed to load data', e)
  } finally {
    loading.value = false
  }
}

async function handleSync() {
  syncing.value = true
  try {
    const result = await triggerSync()
    await loadData()
    lastSync.value = {
      status: result.success ? 'success' : 'error',
      message: { created: result.created, updated: result.updated, errors: result.errors },
      created_at: new Date().toISOString(),
    }
  } catch (e) {
    lastSync.value = { status: 'error', message: { errors: [e.message] }, created_at: new Date().toISOString() }
  } finally {
    syncing.value = false
  }
}

onMounted(loadData)
</script>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.stats {
  display: flex;
  gap: 0.75rem;
  flex-wrap: wrap;
}

.stat {
  background: #fff;
  border-radius: 8px;
  padding: 0.75rem 1.25rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  min-width: 100px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.stat-label {
  font-size: 0.75rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #6b7280;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 700;
}

.stat--active .stat-value { color: #059669; }
.stat--canceled .stat-value { color: #dc2626; }
.stat--past_due .stat-value { color: #d97706; }
.stat--trialing .stat-value { color: #2563eb; }
.stat--unpaid .stat-value { color: #7c3aed; }
</style>
