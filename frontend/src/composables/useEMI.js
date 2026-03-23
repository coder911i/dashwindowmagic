import { ref, computed } from 'vue'

export function useEMI() {
  const price = ref(8500000)
  const downPaymentPercent = ref(20)
  const tenureYears = ref(20)
  const monthlyIncome = ref(150000)
  const interestRate = ref(8.5)

  const loanAmount = computed(() => {
    return price.value * (1 - downPaymentPercent.value / 100)
  })

  const monthlyEMI = computed(() => {
    const P = loanAmount.value
    const R = (interestRate.value / 12) / 100
    const N = tenureYears.value * 12
    
    if (R === 0) return P / N
    
    const emi = (P * R * Math.pow(1 + R, N)) / (Math.pow(1 + R, N) - 1)
    return Math.round(emi)
  })

  const totalInterest = computed(() => {
    return (monthlyEMI.value * tenureYears.value * 12) - loanAmount.value
  })

  const totalPayment = computed(() => {
    return loanAmount.value + totalInterest.value
  })

  const maxEligibleLoan = computed(() => {
    // Basic thumb rule: 50% of monthly income can go to EMI
    const maxEMI = monthlyIncome.value * 0.5
    const R = (interestRate.value / 12) / 100
    const N = tenureYears.value * 12
    
    const maxLoan = (maxEMI * (Math.pow(1 + R, N) - 1)) / (R * Math.pow(1 + R, N))
    return Math.round(maxLoan)
  })

  const isEligible = computed(() => {
    return maxEligibleLoan.value >= loanAmount.value
  })

  return {
    price,
    downPaymentPercent,
    tenureYears,
    monthlyIncome,
    interestRate,
    loanAmount,
    monthlyEMI,
    totalInterest,
    totalPayment,
    maxEligibleLoan,
    isEligible
  }
}
