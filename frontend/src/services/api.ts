// API Service Layer - Professional Backend Integration
class ApiService {
  private baseURL: string
  private token: string | null

  constructor() {
    this.baseURL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api/v1'
    this.token = null
    
    // Load token from localStorage if available
    if (typeof window !== 'undefined') {
      this.token = localStorage.getItem('authToken')
    }
  }

  // Set authentication token
  setToken(token: string | null) {
    this.token = token
    if (typeof window !== 'undefined') {
      if (token) {
        localStorage.setItem('authToken', token)
      } else {
        localStorage.removeItem('authToken')
      }
    }
  }

  // Get headers with authentication
  private getHeaders(): HeadersInit {
    const headers: HeadersInit = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    }

    if (this.token) {
      headers['Authorization'] = `Bearer ${this.token}`
    }

    return headers
  }

  // Generic request method
  private async request<T>(
    endpoint: string, 
    options: RequestInit = {}
  ): Promise<T> {
    const url = `${this.baseURL}${endpoint}`
    
    const config: RequestInit = {
      headers: this.getHeaders(),
      ...options,
    }

    try {
      console.log('🔍 Making API Request:', {
        url: url,
        method: config.method || 'GET',
        headers: config.headers,
        body: config.body ? JSON.parse(config.body as string) : null
      })
      
      const response = await fetch(url, config)
      console.log('📡 Response received:', {
        status: response.status,
        statusText: response.statusText,
        url: response.url,
        headers: Object.fromEntries(response.headers.entries())
      })
      
      let data
      try {
        const textResponse = await response.text()
        console.log('📝 Raw Response:', textResponse)
        data = textResponse ? JSON.parse(textResponse) : {}
      } catch (parseError) {
        console.error('❌ JSON Parse Error:', parseError)
        throw new Error(`Invalid JSON response from server`)
      }

      if (!response.ok) {
        console.error('❌ HTTP Error Response:', { status: response.status, data })
        throw new Error(data.message || data.error || `HTTP ${response.status}: ${response.statusText}`)
      }

      console.log('✅ API Success:', data)
      return data
    } catch (error: any) {
      console.error('💥 API Request Failed:', {
        message: error.message,
        name: error.name,
        stack: error.stack,
        url: url,
        method: config.method || 'GET'
      })
      
      // More specific error handling
      if (error.name === 'TypeError' && error.message.includes('fetch')) {
        error.message = `Network connection failed: Cannot reach ${url}`
      } else if (error.name === 'TypeError') {
        error.message = `Network error: ${error.message}`
      }
      
      throw error
    }
  }

  // Auth endpoints
  async register(userData: {
    name: string
    email: string
    phone: string
    password: string
    password_confirmation: string
    role?: string
    locale?: string
    terms_accepted: boolean
  }) {
    return this.request('/auth/register', {
      method: 'POST',
      body: JSON.stringify(userData),
    })
  }

  async login(credentials: {
    email: string
    password: string
  }) {
    return this.request('/auth/login', {
      method: 'POST',
      body: JSON.stringify(credentials),
    })
  }

  async logout() {
    const result = await this.request('/auth/logout', {
      method: 'POST',
    })
    this.setToken(null)
    return result
  }

  async getProfile() {
    return this.request('/auth/me', {
      method: 'GET',
    })
  }

  async updateProfile(profileData: any) {
    return this.request('/auth/profile', {
      method: 'PUT',
      body: JSON.stringify(profileData),
    })
  }

  async changePassword(passwordData: {
    current_password: string
    password: string
    password_confirmation: string
  }) {
    return this.request('/auth/password/change', {
      method: 'POST',
      body: JSON.stringify(passwordData),
    })
  }

  // Categories endpoints
  async getCategories() {
    return this.request('/categories', {
      method: 'GET',
    })
  }

  async searchCategories(query: string) {
    return this.request(`/categories/search?q=${encodeURIComponent(query)}`, {
      method: 'GET',
    })
  }

  // Cities endpoints
  async getCities() {
    return this.request('/cities', {
      method: 'GET',
    })
  }

  async getPopularCities() {
    return this.request('/cities/popular', {
      method: 'GET',
    })
  }

  // Health check
  async healthCheck() {
    return this.request('/health', {
      method: 'GET',
    })
  }
}

// Create singleton instance
const apiService = new ApiService()

export default apiService
export { ApiService }

// Type definitions for API responses
export interface ApiResponse<T> {
  success: boolean
  message: string
  data: T
  errors?: Record<string, string[]>
}

export interface User {
  id: string
  name: string
  email: string
  phone: string
  role: string
  locale: string
  is_verified: boolean
  rating_avg?: number
  jobs_completed?: number
}

export interface AuthResponse {
  user: User
  token: string
  token_type: string
  expires_at: string
}

export interface Category {
  id: string
  name: string
  name_en: string
  slug: string
  description?: string
  icon?: string
  parent_id?: string
  is_active: boolean
}

export interface City {
  id: string
  name: string
  name_en: string
  slug: string
  region: string
  is_popular: boolean
}
