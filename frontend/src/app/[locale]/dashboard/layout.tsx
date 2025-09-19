import { Metadata } from 'next'

export const metadata: Metadata = {
  title: 'لوحة التحكم - خدمة أب (KhidmaApp)',
  description: 'لوحة تحكم مقدمي الخدمات في منصة خدمة أب',
}

export default function DashboardLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <div className="min-h-screen bg-gray-50">
      {children}
    </div>
  )
}
