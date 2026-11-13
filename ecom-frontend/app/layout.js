import { Geist, Geist_Mono } from "next/font/google";
import "./globals.css";
import { CartProvider } from "./context/CartContext";
import Navbar from "./components/Navbar";
import CartDrawer from "./components/CartDrawer";
import GenderGate from "./components/GenderGate";
import Footer from "./components/Footer";
import { getCategories } from "./utils/api";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

export const metadata = {
  title: "J.G.D Couture - Premium Pakistani Fashion Store",
  description: "A premium fashion house offering high-end Gents Shoes, Footwear, and Female Undergarments with custom variant SKU generation.",
};

export default async function RootLayout({ children }) {
  // Fetch categories at server-side for fast rendering and SEO
  const categories = await getCategories();

  return (
    <html
      lang="en"
      className={`${geistSans.variable} ${geistMono.variable} h-full antialiased`}
    >
      <body className="min-h-full flex flex-col bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-50 transition-colors duration-300">
        <CartProvider>
          <GenderGate />
          <Navbar categories={categories} />
          <CartDrawer />
          <main className="flex-1 flex flex-col">{children}</main>
          <Footer />
        </CartProvider>
      </body>
    </html>
  );
}
