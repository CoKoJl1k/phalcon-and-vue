import axios from 'axios'

const api = axios.create({
  baseURL: '/api',
  headers: { 'Content-Type': 'application/json' },
})

export function fetchSubscriptions(params = {}) {
  return api.get('/subscriptions', { params }).then(r => r.data)
}

export function fetchCustomers() {
  return api.get('/customers').then(r => r.data)
}

export function fetchProducts() {
  return api.get('/products').then(r => r.data)
}

export function triggerSync() {
  return api.post('/sync').then(r => r.data)
}

export function fetchLastSync() {
  return api.get('/sync/last').then(r => r.data)
}
