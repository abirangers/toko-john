import DashboardLayout from "@/Layouts/DashboardLayout";
import { router } from "@inertiajs/react";
import { Button } from "@/Components/ui/button";
import { Pencil } from "lucide-react";
import { User } from "@/types";

export default function ShowUser({ user }: { user: User }) {
    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">Category</h1>
                    <p className="text-sm text-muted-foreground">
                        {user.name}
                    </p>
                </div>
                <Button
                    size="sm"
                    onClick={() =>
                        router.get(route("admin.users.edit", user.id))
                    }
                >
                    <Pencil className="w-3 h-3 mr-2" />
                    Edit
                </Button>
            </div>
            <div className="mt-4 text-secondary">
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-700">
                        User Name
                    </label>
                    <input
                        type="text"
                        value={user.name}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
                <div className="mb-4">
                    <label className="block text-sm font-medium text-gray-7000">
                        Email
                    </label>
                    <input
                        type="text"
                        value={user.email}
                        disabled
                        className="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    />
                </div>
            </div>
        </DashboardLayout>
    );
}
