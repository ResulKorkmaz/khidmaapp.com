// Script to clear all user data from localStorage
// Run this in browser console

localStorage.removeItem('authUser');
localStorage.removeItem('registrationData');
localStorage.removeItem('companyRegistrationData');
localStorage.removeItem('profileData');

console.log('✅ All user data cleared from localStorage');
console.log('🔄 You can now test with fresh data');

// Verify everything is cleared
console.log('📋 authUser:', localStorage.getItem('authUser'));
console.log('📋 registrationData:', localStorage.getItem('registrationData'));
console.log('📋 companyRegistrationData:', localStorage.getItem('companyRegistrationData'));

