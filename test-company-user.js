// Test script to manually create company user in localStorage
// Run this in browser console on http://localhost:3000/ar/dashboard/profile

// Clear existing data first
localStorage.removeItem('authUser');
localStorage.removeItem('registrationData');
localStorage.removeItem('companyRegistrationData');

// Create company user data
const companyUser = {
  id: "test-company-id-123",
  name: "Test Şirketi Ltd",
  email: "test@sirket.com", 
  phone: "0505555555",
  role: "company_provider",
  locale: "ar",
  is_verified: false
};

const companyRegistrationData = {
  userId: "test-company-id-123",
  type: 'company',
  companyName: 'Test Şirketi Ltd',
  authorizedPersonName: 'Ahmet',
  authorizedPersonSurname: 'Yılmaz',
  phone: '0505555555',
  email: 'test@sirket.com',
  serviceCities: ['Riyadh', 'Jeddah'],
  services: ['تنظيف المكاتب', 'صيانة الكمبيوتر'],
  registrationDate: new Date().toISOString()
};

// Set localStorage
localStorage.setItem('authUser', JSON.stringify(companyUser));
localStorage.setItem('registrationData', JSON.stringify(companyRegistrationData));

console.log('✅ Company user data set in localStorage');
console.log('🔄 Testing Security Rules:');
console.log('   1. Go to: http://localhost:3000/ar/dashboard/profile');
console.log('   2. Should auto-redirect to: business-profile (SECURITY RULE)');
console.log('   3. Or directly: http://localhost:3000/ar/dashboard/business-profile');

// Check what we set
console.log('📋 Stored authUser:', JSON.parse(localStorage.getItem('authUser')));
console.log('📋 Stored registrationData:', JSON.parse(localStorage.getItem('registrationData')));
