"use client";

import { useState } from "react";
import { Button, Input } from "@onlineusta/ui";
import Link from "next/link";

export default function ServiceProviderLoginPage() {
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
    <div className="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 pt-24 pb-8 px-4">
      <div className="max-w-md mx-auto space-y-4">
        {/* Main Login Card */}
        <div className="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="mx-auto h-16 w-16 flex items-center justify-center rounded-full bg-indigo-100 mb-4">
              <svg className="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <h1 className="text-2xl font-bold text-gray-900 mb-2">
              Profesyonel Hesabına Giriş Yap
            </h1>
            <p className="text-gray-600 text-sm">
              Hizmet veren olarak platformumuza hoş geldin
            </p>
          </div>

          {/* Login Method Toggle */}
          <div className="flex bg-gray-100 rounded-lg p-1 mb-6">
            <button
              type="button"
              onClick={() => setLoginMethod("email")}
              className={`flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${
                loginMethod === "email"
                  ? "bg-white text-indigo-600 shadow-sm"
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
                  ? "bg-white text-indigo-600 shadow-sm"
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
                    className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
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
                    className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    required
                  />
                </div>

                {/* Forgot Password Link */}
                <div className="text-right">
                  <Link
                    href="/sifremi-unuttum"
                    className="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors"
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
                    className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
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
                    className="w-full h-12 px-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    required
                  />
                </div>

                {/* Forgot Password Link for Phone Login */}
                <div className="text-right">
                  <Link
                    href="/sifremi-unuttum"
                    className="text-sm text-indigo-600 hover:text-indigo-700 font-medium transition-colors"
                  >
                    Şifremi bilmiyorum &gt;&gt;
                  </Link>
                </div>
              </>
            )}

            {/* Login Button */}
            <Button
              type="submit"
              className="w-full h-12 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition-colors shadow-lg hover:shadow-xl"
            >
              Giriş yap
            </Button>
          </form>

          {/* Additional Links */}
          <div className="space-y-3 pt-6 border-t border-gray-200">
            <Link
              href="/hizmet-alan-girisi"
              className="block w-full py-3 px-4 text-center border border-emerald-300 rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors font-medium"
            >
              Hizmet Alan Girişi
            </Link>
            
            <div className="text-center">
              <Link
                href="/hizmet-veren-kaydi"
                className="text-indigo-600 hover:text-indigo-700 font-medium transition-colors"
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