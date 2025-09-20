// INJECT CORRECT COMPANY USER DATA FROM BACKEND
// Run this in browser console - DATA FROM API RESPONSE

console.log('🚀 INJECTING CORRECT COMPANY USER DATA');
console.log('=====================================');

// REAL API RESPONSE DATA (Just got from backend login)
const correctCompanyUser = {
  id: "9fea654b-d52b-4db5-94c7-1203a6c034a9",
  name: "Aptiro LLC",
  email: "info@aptiroglobal.com",
  phone: "0505002999", 
  role: "company_provider",
  locale: "ar",
  is_verified: false
};

const companyToken = "18|WTeZFssbVTbfDEVCUSJ8PqmzrCcSURDePvMuRJx14d7ea9e3";

const companyRegistrationData = {
  userId: correctCompanyUser.id,
  type: 'company',
  companyName: 'Aptiro LLC',
  authorizedPersonName: 'Authorized',
  authorizedPersonSurname: 'Person', 
  phone: correctCompanyUser.phone,
  email: correctCompanyUser.email,
  serviceCities: ['Riyadh', 'Jeddah'],
  services: ['تطوير البرمجيات', 'الاستشارات التقنية'],
  registrationDate: new Date().toISOString()
};

// Clear old data
localStorage.removeItem('authUser');
localStorage.removeItem('registrationData');
localStorage.removeItem('profileData');

// Set correct data
localStorage.setItem('authUser', JSON.stringify(correctCompanyUser));
localStorage.setItem('registrationData', JSON.stringify(companyRegistrationData));

// Set token for API calls
localStorage.setItem('authToken', companyToken);

console.log('✅ CORRECT USER DATA INJECTED:');
console.log('📧 Email:', correctCompanyUser.email);
console.log('🎯 Role:', correctCompanyUser.role);
console.log('🏢 Company:', correctCompanyUser.name);
console.log('🔑 Token set for API calls');

console.log('');
console.log('🔄 NOW REFRESH THE PAGE AND TEST:');
console.log('1. Go to: http://localhost:3000/ar/dashboard/profile');
console.log('2. Should auto-redirect to: /dashboard/business-profile');
console.log('3. Console should show: "🚨 SECURITY: Company user accessing individual profile - REDIRECTING"');

console.log('=====================================');
