// Test script to manually create individual user in localStorage
// Run this in browser console on http://localhost:3000

// Clear existing data first
localStorage.removeItem('authUser');
localStorage.removeItem('registrationData');
localStorage.removeItem('companyRegistrationData');

// Create individual user data
const individualUser = {
  id: "test-individual-id-123",
  name: "Ahmet Yılmaz",
  email: "ahmet@test.com", 
  phone: "0501234567",
  role: "individual_provider",
  locale: "ar",
  is_verified: false
};

const individualRegistrationData = {
  userId: "test-individual-id-123",
  type: 'individual',
  firstName: 'Ahmet',
  lastName: 'Yılmaz',
  phone: '0501234567',
  email: 'ahmet@test.com',
  city: 'Riyadh',
  district: 'Al Olaya',
  services: ['تنظيف المنازل', 'صيانة المكيفات'],
  registrationDate: new Date().toISOString()
};

// Set localStorage
localStorage.setItem('authUser', JSON.stringify(individualUser));
localStorage.setItem('registrationData', JSON.stringify(individualRegistrationData));

console.log('✅ Individual user data set in localStorage');
console.log('🔄 Testing Security Rules:');
console.log('   1. Go to: http://localhost:3000/ar/dashboard/business-profile');
console.log('   2. Should auto-redirect to: profile (SECURITY RULE)');
console.log('   3. Or directly: http://localhost:3000/ar/dashboard/profile');

// Check what we set
console.log('📋 Stored authUser:', JSON.parse(localStorage.getItem('authUser')));
console.log('📋 Stored registrationData:', JSON.parse(localStorage.getItem('registrationData')));

