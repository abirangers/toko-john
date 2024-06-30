import { useState, PropsWithChildren, ReactNode } from "react";
import { User } from "@/types";
import { Link, router } from "@inertiajs/react";
import UserProfile from "@/Components/Navbar/UserProfile";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { Avatar, AvatarFallback } from "@/Components/ui/avatar";
import { ShoppingBag, LogOut, LayoutDashboard } from "lucide-react";
import { cn } from "@/lib/utils";

export default function Authenticated({
    user,
    header,
    children,
}: PropsWithChildren<{ user: User; header?: ReactNode }>) {
    const [showingNavigationDropdown, setShowingNavigationDropdown] =
        useState(false);

    return (
        <div className="min-h-screen">
            <header className="px-4 py-4 bg-white border-b">
                <nav className="flex items-center justify-between">
                    <div>
                        <a className="" href="/">
                            <img
                                src="/images/logo.png"
                                alt="logo penus"
                                loading="lazy"
                                width={32}
                                height={32}
                            />
                        </a>
                    </div>
                    <div>
                        <DropdownMenu>
                            <DropdownMenuTrigger className="outline-none">
                                <Avatar className="w-8 h-8">
                                    <AvatarFallback>
                                        {user.name.charAt(0).toUpperCase()}
                                    </AvatarFallback>
                                </Avatar>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuLabel>
                                    <p className="mb-1 text-sm font-medium">
                                        {user.name}
                                    </p>
                                    <p className="text-xs text-muted-foreground w-[200px] font-normal">
                                        {user.email}
                                    </p>
                                </DropdownMenuLabel>
                                <DropdownMenuSeparator />
                                <DropdownMenuGroup>
                                    <DropdownMenuItem
                                        onClick={() =>
                                            router.post(route("logout"))
                                        }
                                        className="cursor-pointer"
                                    >
                                        <LogOut
                                            className="w-4 h-4 mr-2"
                                            aria-hidden="true"
                                        />
                                        Sign out
                                    </DropdownMenuItem>
                                </DropdownMenuGroup>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </nav>
            </header>
            <aside className="bg-white border-r w-72">
                <div className="px-3 pt-12 mx-auto max-w-7xl">
                    <div className="flex flex-col h-screen">
                        <div className="flex-1 space-y-1">
                            <Link
                                href={""}
                                className={cn(
                                    "text-sm font-medium flex items-center px-4 py-2 text-black rounded hover:bg-gray-100",
                                    route().current("dashboard")
                                        ? "bg-gray-100"
                                        : ""
                                )}
                            >
                                <LayoutDashboard className="w-5 h-5 mr-2" />
                                Dashboard
                            </Link>
                            <Link
                                href={""}
                                className={cn(
                                    "text-sm font-medium flex items-center px-4 py-2 text-black rounded hover:bg-gray-100",
                                    route().current("products.index")
                                        ? "bg-gray-100"
                                        : ""
                                )}
                            >
                                <ShoppingBag className="w-5 h-5 mr-2" />
                                Products
                            </Link>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    );
}
