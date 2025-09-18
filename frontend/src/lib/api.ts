import { z } from 'zod';

// Environment variables
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'https://api.khidmaapp.com';

// API Response Schema
const ApiResponseSchema = z.object({
  success: z.boolean(),
  message: z.string(),
  data: z.any().optional(),
  meta: z.object({
    current_page: z.number(),
    last_page: z.number(),
    per_page: z.number(),
    total: z.number(),
    from: z.number().nullable(),
    to: z.number().nullable(),
  }).optional(),
  links: z.object({
    first: z.string().optional(),
    last: z.string().optional(),
    prev: z.string().nullable(),
    next: z.string().nullable(),
  }).optional(),
});

// Data Schemas
export const CitySchema = z.object({
  id: z.string(),
  name: z.string(),
  name_ar: z.string(),
  name_en: z.string(),
  slug: z.string(),
  slug_ar: z.string(),
  slug_en: z.string(),
  region_code: z.string(),
  region_name: z.string(),
  coordinates: z.object({
    lat: z.number(),
    lng: z.number(),
  }),
  services_count: z.number(),
  is_active: z.boolean(),
  priority: z.number(),
});

export const CategorySchema = z.object({
  id: z.string(),
  name: z.string(),
  name_ar: z.string(),
  name_en: z.string(),
  slug: z.string(),
  slug_ar: z.string(),
  slug_en: z.string(),
  description: z.string().optional(),
  icon: z.string().optional(),
  icon_url: z.string().optional(),
  color: z.string(),
  services_count: z.number(),
  is_active: z.boolean(),
  children: z.array(z.lazy(() => CategorySchema)).optional(),
});

export const UserSchema = z.object({
  id: z.string(),
  name: z.string(),
  email: z.string(),
  avatar_url: z.string(),
  rating_avg: z.number(),
  rating_count: z.number(),
  jobs_completed: z.number(),
  is_verified: z.boolean(),
});

export const ServiceSchema = z.object({
  id: z.string(),
  title: z.string(),
  title_ar: z.string(),
  title_en: z.string(),
  description: z.string(),
  slug: z.string(),
  images: z.array(z.string()),
  featured_image: z.string().optional(),
  budget_min: z.number().optional(),
  budget_max: z.number().optional(),
  budget_currency: z.string(),
  budget_range: z.string(),
  urgency: z.enum(['low', 'medium', 'high', 'urgent']),
  urgency_label: z.string(),
  status: z.enum(['draft', 'active', 'closed', 'completed', 'cancelled']),
  views_count: z.number(),
  bids_count: z.number(),
  is_featured: z.boolean(),
  created_at: z.string(),
  customer: UserSchema.optional(),
  category: CategorySchema.optional(),
  city: CitySchema.optional(),
});

// Type definitions
export type City = z.infer<typeof CitySchema>;
export type Category = z.infer<typeof CategorySchema>;
export type User = z.infer<typeof UserSchema>;
export type Service = z.infer<typeof ServiceSchema>;
export type ApiResponse<T = any> = z.infer<typeof ApiResponseSchema> & { data?: T };

// Error types
export class ApiError extends Error {
  constructor(
    message: string,
    public statusCode: number,
    public response?: any
  ) {
    super(message);
    this.name = 'ApiError';
  }
}

// Base API function
export async function api<T>(
  path: string, 
  options: {
    method?: 'GET' | 'POST' | 'PUT' | 'DELETE';
    body?: any;
    headers?: Record<string, string>;
    revalidate?: number;
    tags?: string[];
  } = {}
): Promise<T> {
  const { 
    method = 'GET', 
    body, 
    headers = {}, 
    revalidate = 300,
    tags = []
  } = options;

  const url = `${API_BASE_URL}${path}`;
  
  const requestOptions: RequestInit = {
    method,
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      ...headers,
    },
    next: {
      revalidate,
      tags: ['api', ...tags],
    },
  };

  if (body && method !== 'GET') {
    requestOptions.body = JSON.stringify(body);
  }

  try {
    const response = await fetch(url, requestOptions);
    
    if (!response.ok) {
      const errorData = await response.json().catch(() => ({}));
      throw new ApiError(
        errorData.message || `HTTP ${response.status}: ${response.statusText}`,
        response.status,
        errorData
      );
    }

    const data = await response.json();
    
    // Validate response structure
    const validatedResponse = ApiResponseSchema.parse(data);
    
    if (!validatedResponse.success) {
      throw new ApiError(validatedResponse.message, 400, data);
    }

    return validatedResponse.data as T;
  } catch (error) {
    if (error instanceof ApiError) {
      throw error;
    }
    
    // Network or parsing errors
    console.error('API Error:', error);
    throw new ApiError(
      'Failed to connect to the API. Please check your connection.',
      0,
      error
    );
  }
}

// Specific API functions
export const CitiesAPI = {
  getAll: (params?: { region?: string; with_services?: boolean }) =>
    api<City[]>('/api/v1/cities', { 
      revalidate: 3600,
      tags: ['cities']
    }),
    
  getPopular: (limit = 12) =>
    api<City[]>(`/api/v1/cities/popular?limit=${limit}`, {
      revalidate: 7200,
      tags: ['cities', 'popular']
    }),
    
  getBySlug: (slug: string) =>
    api<City>(`/api/v1/cities/${slug}`, {
      revalidate: 7200,
      tags: ['cities', slug]
    }),
    
  getCategories: (slug: string, limit = 12) =>
    api<Category[]>(`/api/v1/cities/${slug}/categories?limit=${limit}`, {
      revalidate: 3600,
      tags: ['cities', 'categories', slug]
    }),
};

export const CategoriesAPI = {
  getAll: (params?: { parent?: string; with_services?: boolean }) =>
    api<Category[]>('/api/v1/categories', {
      revalidate: 7200,
      tags: ['categories']
    }),
    
  getRoots: (includeChildren = false) =>
    api<Category[]>(`/api/v1/categories/roots?include_children=${includeChildren}`, {
      revalidate: 14400,
      tags: ['categories', 'roots']
    }),
    
  getPopular: (limit = 12) =>
    api<Category[]>(`/api/v1/categories/popular?limit=${limit}`, {
      revalidate: 7200,
      tags: ['categories', 'popular']
    }),
    
  getBySlug: (slug: string) =>
    api<Category>(`/api/v1/categories/${slug}`, {
      revalidate: 14400,
      tags: ['categories', slug]
    }),
    
  search: (query: string) =>
    api<Category[]>(`/api/v1/categories/search?q=${encodeURIComponent(query)}`, {
      revalidate: 1800,
      tags: ['categories', 'search']
    }),
};

export const ServicesAPI = {
  getAll: (params?: {
    city?: string;
    category?: string;
    q?: string;
    budget_min?: number;
    budget_max?: number;
    urgency?: string;
    page?: number;
    per_page?: number;
  }) => {
    const searchParams = new URLSearchParams();
    if (params) {
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          searchParams.append(key, value.toString());
        }
      });
    }
    
    return api<{
      data: Service[];
      meta: any;
      links: any;
    }>(`/api/v1/services?${searchParams.toString()}`, {
      revalidate: 300,
      tags: ['services']
    });
  },
  
  getFeatured: (limit = 12) =>
    api<Service[]>(`/api/v1/services/featured?limit=${limit}`, {
      revalidate: 900,
      tags: ['services', 'featured']
    }),
    
  getPopular: (period = 'week', limit = 12) =>
    api<Service[]>(`/api/v1/services/popular?period=${period}&limit=${limit}`, {
      revalidate: 1800,
      tags: ['services', 'popular']
    }),
    
  getRecent: (days = 7, limit = 12) =>
    api<Service[]>(`/api/v1/services/recent?days=${days}&limit=${limit}`, {
      revalidate: 300,
      tags: ['services', 'recent']
    }),
    
  getBySlug: (slug: string) =>
    api<Service>(`/api/v1/services/${slug}`, {
      revalidate: 600,
      tags: ['services', slug]
    }),
    
  getByCityAndCategory: (citySlug: string, categorySlug: string, params?: {
    page?: number;
    per_page?: number;
  }) => {
    const searchParams = new URLSearchParams();
    if (params) {
      Object.entries(params).forEach(([key, value]) => {
        if (value !== undefined && value !== null) {
          searchParams.append(key, value.toString());
        }
      });
    }
    
    return api<{
      data: Service[];
      meta: any;
      links: any;
      seo: {
        city: City;
        category: Category;
        total_services: number;
      };
    }>(`/api/v1/services/city/${citySlug}/category/${categorySlug}?${searchParams.toString()}`, {
      revalidate: 1800,
      tags: ['services', citySlug, categorySlug]
    });
  },
};

// Utility function to revalidate API cache
export async function revalidateApi(tags: string[]) {
  if (typeof window === 'undefined') {
    // Server-side revalidation
    const { revalidateTag } = await import('next/cache');
    tags.forEach(tag => revalidateTag(tag));
  }
}
