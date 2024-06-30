import { buttonVariants } from "../ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "../ui/avatar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { User } from "@/types";
import { LogOut, ShoppingBag } from "lucide-react";
import { Link, router } from "@inertiajs/react";

const UserProfile = ({ user }: { user: User }) => {
    return user ? (
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
                    <p className="mb-1 text-sm font-medium">{user.name}</p>
                    <p className="text-xs text-muted-foreground w-[200px] font-normal">
                        {user.email}
                    </p>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuGroup>
                    <DropdownMenuItem
                        onClick={() => router.get(route("order.index"))}
                        className="cursor-pointer"
                    >
                        <ShoppingBag
                            className="w-4 h-4 mr-2"
                            aria-hidden="true"
                        />
                        Orders
                    </DropdownMenuItem>
                    <DropdownMenuItem
                        onClick={() => router.post(route("logout"))}
                        className="cursor-pointer"
                    >
                        <LogOut className="w-4 h-4 mr-2" aria-hidden="true" />
                        Sign out
                    </DropdownMenuItem>
                </DropdownMenuGroup>
            </DropdownMenuContent>
        </DropdownMenu>
    ) : (
        <Link
            href={route("login")}
            className={buttonVariants({
                size: "sm",
            })}
        >
            Sign In
        </Link>
    );
};
export default UserProfile;
