// Açıklama: UI kütüphanesi ana export dosyası
export { Button, buttonVariants, type ButtonProps } from "./components/Button/Button";
// export { ServiceRequestWizard } from "./components/Form/ServiceRequestWizard";
export { Input } from "./components/Input/Input";
export { Textarea } from "./components/Textarea/Textarea";
export { Badge } from "./components/Badge/Badge";

// Toast components
export * from "./components/Toast";

// Offer components
// export { OfferForm } from "./components/Offer/OfferForm";
export { OfferCard } from "./components/Offer/OfferCard";
// export type { OfferFormData } from "./components/Offer/OfferForm";

export { cn } from "./utils/cn";

// Data exports (deprecated - use hooks instead)
export * from "./data/locations";
export * from "./data/services";

// Modern hooks for database-driven data
export { useCities, useDistricts } from './hooks/useLocations';
export { useMainCategories, useSubCategories, useSearchCategories } from './hooks/useCategories';
export { useToast } from './hooks/useToast';
export type { City, District } from './hooks/useLocations';
export type { ServiceCategory } from './hooks/useCategories'; 