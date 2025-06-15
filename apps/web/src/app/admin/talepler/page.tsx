// Açıklama: Admin panel - hizmet talepleri yönetimi
import { Metadata } from "next";
import { Button } from "@onlineusta/ui";

export const metadata: Metadata = {
  title: "Hizmet Talepleri | Admin Panel",
  description: "Hizmet taleplerini yönetin",
};

// Mock data - real data would come from API
const mockServiceRequests = [
  {
    id: "req_001",
    title: "Ev Genel Temizliği",
    description: "3+1 dairem için genel temizlik hizmeti",
    category: {
      name: "Ev Temizliği",
      slug: "ev-temizligi",
    },
    customer: {
      firstName: "Ahmet",
      lastName: "Yılmaz",
      avatar: null,
    },
    city: "İstanbul",
    district: "Kadıköy",
    budget: 500,
    status: "PUBLISHED",
    priority: "NORMAL",
    createdAt: "2024-01-15T10:30:00Z",
    _count: {
      offers: 3,
    },
  },
  {
    id: "req_002",
    title: "Elektrik Arızası Onarımı",
    description: "Sigortalar atıyor, elektrik kesiliyor",
    category: {
      name: "Elektrik",
      slug: "elektrik",
    },
    customer: {
      firstName: "Mehmet",
      lastName: "Kaya",
      avatar: null,
    },
    city: "Ankara",
    district: "Çankaya",
    budget: null,
    status: "PUBLISHED",
    priority: "URGENT",
    createdAt: "2024-01-14T15:45:00Z",
    _count: {
      offers: 1,
    },
  },
];

const statusColors = {
  DRAFT: "bg-gray-100 text-gray-800",
  PUBLISHED: "bg-blue-100 text-blue-800",
  IN_PROGRESS: "bg-yellow-100 text-yellow-800",
  COMPLETED: "bg-green-100 text-green-800",
  CANCELLED: "bg-red-100 text-red-800",
  EXPIRED: "bg-gray-100 text-gray-800",
};

const priorityColors = {
  LOW: "bg-gray-100 text-gray-800",
  NORMAL: "bg-blue-100 text-blue-800",
  HIGH: "bg-orange-100 text-orange-800",
  URGENT: "bg-red-100 text-red-800",
};

export default function AdminServiceRequestsPage() {
  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString("tr-TR", {
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit",
    });
  };

  const formatCurrency = (amount: number | null) => {
    if (!amount) return "Belirtilmemiş";
    return new Intl.NumberFormat("tr-TR", {
      style: "currency",
      currency: "TRY",
    }).format(amount);
  };

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="container mx-auto px-4 py-8">
        {/* Header */}
        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Hizmet Talepleri</h1>
            <p className="text-gray-600 mt-2">
              Tüm hizmet taleplerini görüntüleyin ve yönetin
            </p>
          </div>
          <div className="flex gap-3">
            <Button variant="outline">
              Filtrele
            </Button>
            <Button variant="outline">
              Dışa Aktar
            </Button>
          </div>
        </div>

        {/* Filters */}
        <div className="bg-white rounded-lg shadow-sm p-6 mb-6">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Kategori
              </label>
              <select className="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tüm Kategoriler</option>
                <option value="ev-temizligi">Ev Temizliği</option>
                <option value="elektrik">Elektrik</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Durum
              </label>
              <select className="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tüm Durumlar</option>
                <option value="PUBLISHED">Yayında</option>
                <option value="IN_PROGRESS">Devam Ediyor</option>
                <option value="COMPLETED">Tamamlandı</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Şehir
              </label>
              <select className="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tüm Şehirler</option>
                <option value="istanbul">İstanbul</option>
                <option value="ankara">Ankara</option>
              </select>
            </div>
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-2">
                Öncelik
              </label>
              <select className="w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Tüm Öncelikler</option>
                <option value="URGENT">Acil</option>
                <option value="HIGH">Yüksek</option>
                <option value="NORMAL">Normal</option>
              </select>
            </div>
          </div>
        </div>

        {/* Service Requests Table */}
        <div className="bg-white rounded-lg shadow-sm overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Talep
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Müşteri
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kategori
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Konum
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Bütçe
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Durum
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Teklifler
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Tarih
                  </th>
                  <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                    İşlemler
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {mockServiceRequests.map((request) => (
                  <tr key={request.id} className="hover:bg-gray-50">
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div>
                        <div className="text-sm font-medium text-gray-900">
                          {request.title}
                        </div>
                        <div className="text-sm text-gray-500 max-w-xs truncate">
                          {request.description}
                        </div>
                        <div className="flex items-center mt-1 gap-2">
                          <span
                            className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                              priorityColors[request.priority as keyof typeof priorityColors]
                            }`}
                          >
                            {request.priority === "URGENT" && "Acil"}
                            {request.priority === "HIGH" && "Yüksek"}
                            {request.priority === "NORMAL" && "Normal"}
                            {request.priority === "LOW" && "Düşük"}
                          </span>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center">
                        <div className="h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center">
                          {request.customer.firstName[0]}
                        </div>
                        <div className="ml-3">
                          <div className="text-sm font-medium text-gray-900">
                            {request.customer.firstName} {request.customer.lastName}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">
                        {request.category.name}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">
                        {request.city}
                      </div>
                      <div className="text-sm text-gray-500">
                        {request.district}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-900">
                        {formatCurrency(request.budget)}
                      </div>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap">
                      <span
                        className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                          statusColors[request.status as keyof typeof statusColors]
                        }`}
                      >
                        {request.status === "PUBLISHED" && "Yayında"}
                        {request.status === "IN_PROGRESS" && "Devam Ediyor"}
                        {request.status === "COMPLETED" && "Tamamlandı"}
                        {request.status === "CANCELLED" && "İptal Edildi"}
                        {request.status === "DRAFT" && "Taslak"}
                        {request.status === "EXPIRED" && "Süresi Doldu"}
                      </span>
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      {request._count.offers} teklif
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {formatDate(request.createdAt)}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <Button variant="ghost" size="sm">
                        Görüntüle
                      </Button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Pagination */}
        <div className="flex items-center justify-between mt-6">
          <div className="text-sm text-gray-700">
            <span className="font-medium">2</span> talepten{" "}
            <span className="font-medium">1-2</span> arası gösteriliyor
          </div>
          <div className="flex gap-2">
            <Button variant="outline" size="sm" disabled>
              Önceki
            </Button>
            <Button variant="outline" size="sm" disabled>
              Sonraki
            </Button>
          </div>
        </div>
      </div>
    </div>
  );
} 