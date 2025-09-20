// Debug script for current user in localStorage
// Run this in browser console to check current user data

console.log('🔍 CURRENT USER DEBUG:');
console.log('====================');

const authUser = localStorage.getItem('authUser');
const registrationData = localStorage.getItem('registrationData');
const companyRegistrationData = localStorage.getItem('companyRegistrationData');

console.log('📋 authUser:', authUser ? JSON.parse(authUser) : 'NOT FOUND');
console.log('📋 registrationData:', registrationData ? JSON.parse(registrationData) : 'NOT FOUND');  
console.log('📋 companyRegistrationData:', companyRegistrationData ? JSON.parse(companyRegistrationData) : 'NOT FOUND');

if (authUser) {
  const user = JSON.parse(authUser);
  console.log('🎯 Current Role:', user.role);
  console.log('📧 Email:', user.email);
  
  if (user.email === 'info@aptiroglobal.com') {
    console.log('✅ Found the user: info@aptiroglobal.com');
    
    if (user.role !== 'company_provider') {
      console.log('🚨 PROBLEM: Role should be company_provider but is:', user.role);
      console.log('🔧 FIXING: Setting role to company_provider...');
      
      // Fix the role
      user.role = 'company_provider';
      localStorage.setItem('authUser', JSON.stringify(user));
      
      console.log('✅ FIXED: Role updated to company_provider');
      console.log('🔄 Please refresh the page to see changes');
    } else {
      console.log('✅ Role is correct: company_provider');
    }
  }
} else {
  console.log('❌ No user found in localStorage');
}

console.log('====================');

