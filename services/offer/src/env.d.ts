// Açıklama: Environment değişken tipleri
declare namespace NodeJS {
  interface ProcessEnv {
    PORT?: string;
    NODE_ENV?: 'development' | 'production' | 'test';
    DATABASE_URL: string;
    ALLOWED_ORIGINS?: string;
    NATS_URL?: string;
    WS_PORT?: string;
    STRIPE_SECRET_KEY?: string;
    STRIPE_PUBLISHABLE_KEY?: string;
    LOG_LEVEL?: string;
  }
} 