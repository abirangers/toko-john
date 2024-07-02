import { PropsWithChildren, useEffect } from "react";
import { User } from "@/types";
import { Link, router, usePage } from "@inertiajs/react";
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
import {
    LogOut,
    LayoutDashboard,
    Image,
    School,
    GraduationCap,
    Package,
    List,
    User as UserIcon,
} from "lucide-react";
import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from "@/Components/ui/accordion";
import { cn } from "@/lib/utils";
import { toast } from "sonner";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";

const DashboardLink = ({
    href,
    name,
    icon,
    className,
}: {
    href: string;
    name: string;
    icon: React.ReactNode;
    className?: string;
}) => (
    <Link
        href={route(href)}
        className={cn(
            "text-sm font-medium flex items-center px-4 py-2 text-black rounded hover:bg-gray-100",
            route().current(href) ? "bg-gray-100" : "",
            className
        )}
    >
        {icon}
        {name}
    </Link>
);

export default function DashboardLayout({ children }: PropsWithChildren) {
    const { auth, flash } = usePage().props as unknown as {
        auth: { user: User };
        flash: { success: string; error: string };
    };
    const user = auth?.user;

    useEffect(() => {
        if (flash?.success) {
            toast.success(flash.success);
        }
        if (flash?.error) {
            toast.error(flash.error);
        }
    }, [flash]);

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
                                        {user?.name.charAt(0).toUpperCase()}
                                    </AvatarFallback>
                                </Avatar>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end">
                                <DropdownMenuLabel>
                                    <p className="mb-1 text-sm font-medium">
                                        {user?.name}
                                    </p>
                                    <p className="text-xs text-muted-foreground w-[200px] font-normal">
                                        {user?.email}
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
            <div className="flex">
                <aside className="flex-shrink-0 bg-white border-r w-72">
                    <div className="px-3 pt-12 mx-auto max-w-7xl">
                        <div className="flex flex-col h-screen">
                            <div className="flex-1 space-y-2">
                                <DashboardLink
                                    href="admin.dashboard"
                                    name="Dashboard"
                                    icon={
                                        <LayoutDashboard className="w-5 h-5 mr-2" />
                                    }
                                />
                                <DashboardLink
                                    href="admin.media.index"
                                    name="Media"
                                    icon={<Image className="w-5 h-5 mr-2" />}
                                />
                                <DashboardLink
                                    href="admin.majors.index"
                                    name="Major"
                                    icon={
                                        <GraduationCap className="w-5 h-5 mr-2" />
                                    }
                                />
                                <DashboardLink
                                    href="admin.classes.index"
                                    name="Class"
                                    icon={<School className="w-5 h-5 mr-2" />}
                                />
                                <DashboardLink
                                    href="admin.categories.index"
                                    name="Category"
                                    icon={<List className="w-5 h-5 mr-2" />}
                                />
                                <DashboardLink
                                    href="admin.products.index"
                                    name="Products"
                                    icon={<Package className="w-5 h-5 mr-2" />}
                                />
                                <Accordion
                                    type="multiple"
                                    className="text-sm font-medium text-black"
                                >
                                    <AccordionItem
                                        value="authentication"
                                        className="p-0"
                                    >
                                        <AccordionTrigger className="flex items-center justify-between px-4 pt-0">
                                            <div className="flex items-center">
                                                <UserIcon className="w-5 h-5 mr-2" />
                                                Authentication
                                            </div>
                                        </AccordionTrigger>
                                        <AccordionContent className="px-0 pl-4">
                                            <DashboardLink
                                                href="admin.users.index"
                                                name="Users"
                                                icon={
                                                    <UserIcon className="w-5 h-5 mr-2" />
                                                }
                                                className="px-0"
                                            />
                                        </AccordionContent>
                                    </AccordionItem>
                                </Accordion>
                            </div>
                        </div>
                    </div>
                </aside>
                <main className="flex-1 p-8 overflow-hidden">
                    <BreadcrumbWrapper />
                    {children}
                </main>
            </div>
        </div>
    );
}
