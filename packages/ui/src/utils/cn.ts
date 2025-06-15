// Açıklama: Tailwind sınıflarını merge etmek için utility fonksiyonu
import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
  return twMerge(clsx(inputs));
} 