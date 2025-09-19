import { redirect } from 'next/navigation'

export default function RootPage() {
  // Redirect to Arabic locale by default
  redirect('/ar')
}