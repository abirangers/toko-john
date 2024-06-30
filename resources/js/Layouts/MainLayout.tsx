import { PropsWithChildren, ReactNode } from "react";
import { User } from "@/types";
import Navbar from "@/Components/Navbar/Navbar";
import Footer from "@/Components/Footer/Footer";

export default function MainLayout({
    user,
    children,
}: PropsWithChildren<{ user: User }>) {
    return (
        <div className="min-h-screen">
            <Navbar user={user} />
            <main>
                {children}
            </main>
            <Footer />
        </div>
    );
}
