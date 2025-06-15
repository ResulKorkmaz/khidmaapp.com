// Açıklama: Teklif gönderme sayfası
import { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { OfferSubmissionPage } from './OfferSubmissionPage';

interface Props {
  params: { requestId: string };
}

export async function generateMetadata({ params }: Props): Promise<Metadata> {
  return {
    title: 'Teklif Gönder - OnlineUsta.com.tr',
    description: 'Hizmet talebine teklif gönderin ve iş fırsatını değerlendirin.',
  };
}

export default async function TeklifGonderPage({ params }: Props) {
  const { requestId } = params;

  if (!requestId) {
    notFound();
  }

  return <OfferSubmissionPage requestId={requestId} />;
} 