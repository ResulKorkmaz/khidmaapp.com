import { useState, useEffect } from 'react';

export interface City {
  id: number;
  name: string;
  slug: string;
  plateCode?: string;
  region?: string;
}

export interface District {
  id: number;
  name: string;
  slug: string;
}

export function useCities() {
  const [cities, setCities] = useState<City[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchCities = async () => {
      try {
        const response = await fetch('/api/locations');
        const data = await response.json();
        
        if (data.success) {
          setCities(data.cities);
        } else {
          setError(data.error || 'Şehir verileri alınamadı');
        }
      } catch (err) {
        setError('Şehir verileri alınamadı');
        console.error('Cities fetch error:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchCities();
  }, []);

  return { cities, loading, error };
}

export function useDistricts(cityId: number | null) {
  const [districts, setDistricts] = useState<District[]>([]);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    if (!cityId) {
      setDistricts([]);
      return;
    }

    const fetchDistricts = async () => {
      setLoading(true);
      setError(null);
      
      try {
        const response = await fetch(`/api/locations?cityId=${cityId}`);
        const data = await response.json();
        
        if (data.success) {
          setDistricts(data.districts);
        } else {
          setError(data.error || 'İlçe verileri alınamadı');
        }
      } catch (err) {
        setError('İlçe verileri alınamadı');
        console.error('Districts fetch error:', err);
      } finally {
        setLoading(false);
      }
    };

    fetchDistricts();
  }, [cityId]);

  return { districts, loading, error };
} 