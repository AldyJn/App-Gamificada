import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

const appName = import.meta.env.VITE_APP_NAME || 'ClassCraft Destiny'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  
  resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  
  setup({ el, App, props, plugin }) {
    return createApp({ render: () => h(App, props) })
      .use(plugin)
      .mount(el)
  },
  
  progress: {
    color: '#C7B88A',
    showSpinner: true,
  },
})