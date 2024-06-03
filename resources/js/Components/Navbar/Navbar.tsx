import { cn } from "@/lib/utils";

import { Link } from "@inertiajs/react";

import { ShoppingCart } from "lucide-react";
import { Button, buttonVariants } from "../ui/button";
import Logo from "./Logo";
import DesktopNav from "./DesktopNav";
import MobileNav from "./MobileNav";
import CartIcon from "./CartIcon";
import UserProfile from "./UserProfile";
import { PageProps, User } from "@/types";

const Navbar = ({ user }: { user: User }) => {
    return (
        <header className="sticky top-0 z-50 w-full py-3 border-b bg-background">
            <nav className="flex justify-between mx-6">
                {/*left navbar*/}
                <DesktopNav />
                <MobileNav />

                {/* right navbar */}
                <div className="flex items-center justify-center gap-2">
                    <CartIcon />
                    <UserProfile user={user} />
                </div>
            </nav>
        </header>
    );
};

export default Navbar;
