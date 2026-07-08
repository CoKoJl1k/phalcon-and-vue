<template>
  <div class="app">
    <header class="header">
      <h1>Subscription Sync Dashboard</h1>
    </header>
    <main class="main">
      <SubscriptionDashboard />
    </main>
  </div>
</template>

<script setup>
import SubscriptionDashboard from './components/SubscriptionDashboard.vue'
</script>

<style>
*,
*::before,
*::after {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
  background: #f5f7fa;
  color: #1a1a2e;
  line-height: 1.6;
}

.app {
  min-height: 100vh;
}

.header {
  background: #1a1a2e;
  color: #fff;
  padding: 1rem 2rem;
}

.header h1 {
  font-size: 1.25rem;
  font-weight: 600;
}

.main {
  max-width: 1200px;
  margin: 0 auto;
  padding: 1.5rem 2rem;
}
</style>
