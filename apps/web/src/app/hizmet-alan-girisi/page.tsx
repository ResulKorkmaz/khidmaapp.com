"use client";

import { useState } from "react";
import { Button, Input } from "@onlineusta/ui";
import Link from "next/link";

export default function ServiceRequesterLoginPage() {
  const [loginMethod, setLoginMethod] = useState<"email" | "phone">("email");
  const [formData, setFormData] = useState({
    email: "",
    password: "",
    phone: "",
  });

  const handleInputChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Handle login logic here
    console.log("Login attempt:", formData);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50 pt-24 pb-8 px-4">
      <div className="max-w-md mx-auto space-y-4">
        {/* Main Login Card */}
        <div className="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
          {/* Header */}
          <div className="text-center mb-8">
            <h1 className="text-2xl font-bold text-gray-900 mb-2">
              Hesabına erişmek için giriş yap
            </h1>
          </div>

          {/* Login Method Toggle */}
          <div className="flex bg-gray-100 rounded-lg p-1 mb-6">
            <button
              type="button"
              onClick={() => setLoginMethod("email")}
              className={`flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${
                loginMethod === "email"
                  ? "bg-white text-emerald-600 shadow-sm"
                  : "text-gray-600 hover:text-gray-900"
              }`}
            >
              Email ile giriş
            </button>
            <button
              type="button"
              onClick={() => setLoginMethod("phone")}
              className={`flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${
                loginMethod === "phone"
                  ? "bg-white text-emerald-600 shadow-sm"
                  : "text-gray-600 hover:text-gray-900"
              }`}
            >
              Telefon ile giriş
            </button>
          </div>

          {/* Login Form */}
          <form onSubmit={handleSubmit} className="space-y-6">
            {loginMethod === "email" ? (
              <>
                {/* Email Field */}
                <div>
                  <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
                    Email
                  </label>
                  <Input
                    id="email"
                    name="email"
                    type="email"
                    placeholder="Email adresini gir"
                    value={formData.email}
                    onChange={handleInputChange}
                    className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                    required
                  />
                </div>

                {/* Password Field */}
                <div>
                  <label htmlFor="password" className="block text-sm font-medium text-gray-700 mb-2">
                    Şifre
                  </label>
                  <Input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Şifreni gir"
                    value={formData.password}
                    onChange={handleInputChange}
                    className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                    required
                  />
                </div>

                {/* Forgot Password Link */}
                <div className="text-right">
                  <Link
                    href="/sifremi-unuttum"
                    className="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
                  >
                    Şifremi bilmiyorum &gt;&gt;
                  </Link>
                </div>
              </>
                         ) : (
               <>
                 {/* Phone Field */}
                 <div>
                   <label htmlFor="phone" className="block text-sm font-medium text-gray-700 mb-2">
                     Telefon Numarası
                   </label>
                   <Input
                     id="phone"
                     name="phone"
                     type="tel"
                     placeholder="Telefon numaranı gir"
                     value={formData.phone}
                     onChange={handleInputChange}
                     className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                     required
                   />
                 </div>

                 {/* Password Field for Phone Login */}
                 <div>
                   <label htmlFor="phonePassword" className="block text-sm font-medium text-gray-700 mb-2">
                     Şifre
                   </label>
                   <Input
                     id="phonePassword"
                     name="password"
                     type="password"
                     placeholder="Şifreni gir"
                     value={formData.password}
                     onChange={handleInputChange}
                     className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                     required
                   />
                 </div>

                 {/* Forgot Password Link for Phone Login */}
                 <div className="text-right">
                   <Link
                     href="/sifremi-unuttum"
                     className="text-sm text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
                   >
                     Şifremi bilmiyorum &gt;&gt;
                   </Link>
                 </div>
               </>
             )}

            {/* Login Button */}
            <Button
              type="submit"
              className="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors shadow-lg hover:shadow-xl"
            >
              Giriş yap
            </Button>
          </form>

          {/* Additional Links */}
          <div className="space-y-3 pt-6 border-t border-gray-200">
            <Link
              href="/hizmet-veren-girisi"
              className="block w-full py-3 px-4 text-center border border-indigo-300 rounded-lg text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-colors font-medium"
            >
              Hizmet Veren Girişi
            </Link>
            
            <div className="text-center">
              <Link
                href="/hizmet-veren-kaydi"
                className="text-emerald-600 hover:text-emerald-700 font-medium transition-colors"
              >
                Hesabın yok mu? Hemen kayıt ol
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
} 