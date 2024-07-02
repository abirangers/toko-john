import React from "react";
import { useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { Button } from "@/Components/ui/button";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Class, Major } from "@/types";
import { toast } from "sonner";
import BreadcrumbWrapper from "@/Components/BreadcrumbWrapper";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select";

export default function ManageClass({
    classData,
    majors,
}: {
    classData: Class;
    majors: Major[];
}) {
    const title = classData ? "Edit Class" : "Create Class";
    const description = classData ? "Edit class" : "Add a new class";

    const form = useForm({
        name: classData?.name ?? "",
        major_id: classData?.major.id.toString() ?? "",
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();

        if (classData) {
            form.patch(route("admin.classes.update", classData?.id), {
                onError: (error: any) => {
                    console.log(error);
                    form.setError(error);
                    Object.values(form.errors).map((error: any) => {
                        toast.error(`error brow ${error}`);
                    });
                },
            });
        } else {
            form.post(route("admin.classes.store"), {
                onError: (error: any) => {
                    toast.error(error);
                    console.log(error);
                },
            });
        }
    };

    return (
        <DashboardLayout>
            <div className="flex justify-between py-4 border-b-[1.5px]">
                <div>
                    <h1 className="text-3xl font-bold ">{title}</h1>
                    <p className="text-sm text-muted-foreground">
                        {description}
                    </p>
                </div>
            </div>
            <form onSubmit={handleSubmit} className="mt-4">
                <div className="mb-4">
                    <Label htmlFor="name">
                        Name<span className="text-red-600">*</span>
                    </Label>
                    <Input
                        id="name"
                        name="name"
                        value={form.data.name}
                        onChange={(e) => form.setData("name", e.target.value)}
                        className="mt-2"
                    />
                    {form.errors.name && (
                        <div className="text-red-600">{form.errors.name}</div>
                    )}
                </div>
                <div className="mb-4">
                    <Label htmlFor="major">
                        Major<span className="text-red-600">*</span>
                    </Label>
                    <Select
                        value={form.data.major_id}
                        onValueChange={(value: string) =>
                            form.setData("major_id", value)
                        }
                    >
                        <SelectTrigger>
                            <SelectValue placeholder="Select a major" />
                        </SelectTrigger>
                        <SelectContent>
                            {majors.map((major) => (
                                <SelectItem
                                    key={major.id}
                                    value={major.id.toString()}
                                >
                                    {major.name}
                                </SelectItem>
                            ))}
                        </SelectContent>
                    </Select>
                    {form.errors.major_id && (
                        <div className="text-red-600">
                            {form.errors.major_id}
                        </div>
                    )}
                </div>

                <Button
                    type="submit"
                    className="rounded-md"
                    disabled={form.processing}
                >
                    {classData ? "Update Class" : "Add Class"}
                </Button>
            </form>
        </DashboardLayout>
    );
}
