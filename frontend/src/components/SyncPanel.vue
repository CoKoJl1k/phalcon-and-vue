<template>
  <div class="sync-panel">
    <div class="sync-info">
      <span class="sync-label">Last sync:</span>
      <span v-if="lastSync" class="sync-result">
        <span :class="['sync-badge', lastSync.status === 'success' ? 'sync-badge--success' : 'sync-badge--error']">
          {{ lastSync.status }}
        </span>
        <span class="sync-time">{{ formatDate(lastSync.created_at) }}</span>
        <span v-if="lastSync.message" class="sync-details">
          ({{ lastSync.message.created ?? 0 }} created, {{ lastSync.message.updated ?? 0 }} updated
          <span v-if="lastSync.message.errors?.length" class="sync-errors">
            , {{ lastSync.message.errors.length }} error(s)
          </span>)
        </span>
      </span>
      <span v-else class="sync-none">Never</span>
    </div>
    <button
      class="sync-btn"
      :disabled="syncing"
      @click="$emit('sync')"
    >
      {{ syncing ? 'Syncing...' : 'Sync Now' }}
    </button>
  </div>
</template>

<script setup>
defineProps({
  syncing: Boolean,
  lastSync: Object,
})

defineEmits(['sync'])

function formatDate(dateStr) {
  if (!dateStr) return ''
  const d = new Date(dateStr)
  return d.toLocaleString()
}
</script>

<style scoped>
.sync-panel {
  background: #fff;
  border-radius: 8px;
  padding: 1rem 1.25rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.sync-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.sync-label {
  font-weight: 600;
  color: #374151;
}

.sync-none {
  color: #9ca3af;
  font-style: italic;
}

.sync-result {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  flex-wrap: wrap;
}

.sync-badge {
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  padding: 0.125rem 0.5rem;
  border-radius: 4px;
}

.sync-badge--success {
  background: #d1fae5;
  color: #065f46;
}

.sync-badge--error {
  background: #fee2e2;
  color: #991b1b;
}

.sync-time {
  color: #6b7280;
  font-size: 0.875rem;
}

.sync-details {
  color: #6b7280;
  font-size: 0.875rem;
}

.sync-errors {
  color: #dc2626;
}

.sync-btn {
  background: #1a1a2e;
  color: #fff;
  border: none;
  border-radius: 6px;
  padding: 0.5rem 1.25rem;
  font-size: 0.875rem;
  font-weight: 600;
  cursor: pointer;
  transition: opacity 0.15s;
  white-space: nowrap;
}

.sync-btn:hover:not(:disabled) {
  opacity: 0.85;
}

.sync-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>
