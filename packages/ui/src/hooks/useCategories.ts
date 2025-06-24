import { useState, useEffect } from 'react';

export interface ServiceCategory {
  id: number;
  name: string;
  slug: string;
  description?: string;
  icon?: string;
  color?: string;
  parent?: {
    id: number;
    name: string;
    slug: string;
  };
  _count?: {
    children: number;
  };
}

export function useMainCategories() {
  const [categories, setCategories] = useState<ServiceCategory[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchCategories = async () => {
      try {
        const response = await fetch('/api/categories');
        const data = await response.json();
        
        if (data.success) {
          setCategories(data.categories);
        } else {
          setError(data.error || 'Kategori verileri alınamadı');
        }
      } catch (err) {
        setError('Kategori verileri alınamadı');
        console.error('Categories fetch error:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchCategories();
  }, []);

  return { categories, loading, error };
}

export function useSubCategories(parentId: number | null) {
  const [categories, setCategories] = useState<ServiceCategory[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!parentId) {
      setCategories([]);
      return;
    }

    const fetchSubCategories = async () => {
      setLoading(true);
      setError(null);
      
      try {
        const response = await fetch(`/api/categories?parentId=${parentId}`);
        const data = await response.json();
        
        if (data.success) {
          setCategories(data.categories);
        } else {
          setError(data.error || 'Alt kategori verileri alınamadı');
        }
      } catch (err) {
        setError('Alt kategori verileri alınamadı');
        console.error('Sub categories fetch error:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchSubCategories();
  }, [parentId]);

  return { categories, loading, error };
}

export function useSearchCategories(searchTerm: string) {
  const [categories, setCategories] = useState<ServiceCategory[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!searchTerm || searchTerm.length < 2) {
      setCategories([]);
      return;
    }

    const fetchSearchResults = async () => {
      setLoading(true);
      setError(null);
      
      try {
        const response = await fetch(`/api/categories?search=${encodeURIComponent(searchTerm)}`);
        const data = await response.json();
        
        if (data.success) {
          setCategories(data.categories);
        } else {
          setError(data.error || 'Arama sonuçları alınamadı');
        }
      } catch (err) {
        setError('Arama sonuçları alınamadı');
        console.error('Search categories error:', err);
      } finally {
        setLoading(false);
      }
    };

    const timeoutId = setTimeout(fetchSearchResults, 300); // Debounce
    return () => clearTimeout(timeoutId);
  }, [searchTerm]);

  return { categories, loading, error };
} 