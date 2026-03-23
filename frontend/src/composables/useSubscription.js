import { onMounted } from 'vue'

export function useRazorpay() {
  const loadScript = () => {
    return new Promise((resolve) => {
      if (window.Razorpay) {
        resolve(true)
        return
      }
      const script = document.createElement('script')
      script.src = 'https://checkout.razorpay.com/v1/checkout.js'
      script.onload = () => resolve(true)
      script.onerror = () => resolve(false)
      document.body.appendChild(script)
    })
  }

  const openCheckout = async (options, onSuccess) => {
    const loaded = await loadScript()
    if (!loaded) {
      alert('Razorpay SDK failed to load. Are you online?')
      return
    }

    const rzp = new window.Razorpay({
      ...options,
      handler: (response) => {
        onSuccess(response)
      },
      prefill: {
        name: 'Agent Name',
        email: 'agent@example.com'
      },
      theme: { color: '#2563EB' }
    })
    rzp.open()
  }

  return { openCheckout }
}
