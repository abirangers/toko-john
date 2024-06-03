import * as React from "react";
import { Button, buttonVariants } from "../ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "../ui/avatar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { User } from "@/types";
import { LogOut } from "lucide-react";

const UserProfile = ({ user }: { user: User }) => {
    return !user ? (
        <DropdownMenu>
            <DropdownMenuTrigger className="outline-none">
                <Avatar className="w-8 h-8">
                    <AvatarImage
                        src="/images/product1.jpg"
                        className="object-cover"
                    />
                    <AvatarFallback>CN</AvatarFallback>
                </Avatar>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">
                <DropdownMenuLabel>
                    <p className="mb-1 text-sm font-medium">abi</p>
                    <p className="text-xs text-muted-foreground w-[200px] font-normal">
                        abi@gmail.com
                    </p>
                </DropdownMenuLabel>
                <DropdownMenuSeparator />
                <DropdownMenuItem>
                    <LogOut className="w-4 h-4 mr-2" aria-hidden="true" />
                    Sign out
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    ) : (
        <Button
            className={buttonVariants({
                size: "sm",
            })}
        >
            Sign In
        </Button>
    );
};

export default UserProfile;
