import React from 'react';
import { Toast, ToastProps } from './Toast';

interface ToastContainerProps {
  toasts: ToastProps[];
  onRemove: (id: string) => void;
}

export const ToastContainer: React.FC<ToastContainerProps> = ({ toasts, onRemove }) => {
  if (toasts.length === 0) return null;

  return (
    <div className="fixed inset-0 z-50 pointer-events-none">
      {/* Desktop: Top right with better positioning */}
      <div className="hidden sm:block">
        <div className="fixed top-4 right-4 w-full max-w-sm space-y-3">
          {toasts.map((toast) => (
            <Toast key={toast.id} {...toast} onRemove={onRemove} />
          ))}
        </div>
      </div>
      
      {/* Mobile: Top center */}
      <div className="sm:hidden">
        <div className="fixed top-4 left-4 right-4 space-y-3">
          {toasts.map((toast) => (
            <Toast key={toast.id} {...toast} onRemove={onRemove} />
          ))}
        </div>
      </div>
    </div>
  );
}; 