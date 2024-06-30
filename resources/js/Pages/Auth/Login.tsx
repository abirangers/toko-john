import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Head, useForm } from "@inertiajs/react";
import GuestLayout from "@/Layouts/GuestLayout";
import { ChangeEvent, FormEvent, FormEventHandler, useEffect } from "react";
import InputError from "@/Components/InputError";
import { Checkbox } from "@/Components/ui/checkbox";

export default function Login({
    status,
    canResetPassword,
}: {
    status?: string;
    canResetPassword: boolean;
}) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset("password");
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route("login"));
    };

    return (
        <GuestLayout>
            <Head title="Log in" />
            {status && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="my-auto">
                <Card className="w-full max-w-sm">
                    <CardHeader>
                        <CardTitle className="text-2xl text-secondary">
                            Login
                        </CardTitle>
                        <CardDescription>
                            Enter your email below to login to your account.
                        </CardDescription>
                    </CardHeader>
                    <CardContent className="space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="block w-full mt-1"
                                autoComplete="username"
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                            />

                            <InputError message={errors.email} />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="password">Password</Label>
                            <Input
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className="block w-full mt-1"
                                autoComplete="current-password"
                                onChange={(e) =>
                                    setData("password", e.target.value)
                                }
                            />

                            <InputError message={errors.password} />
                        </div>
                        <div className="flex space-x-2 items-top">
                            <Checkbox
                                name="remember"
                                checked={data.remember}
                                onCheckedChange={(checked: boolean) => {
                                    setData('remember', checked)
                                }}
                            />
                            <div className="grid gap-1.5 leading-none">
                                <label
                                    htmlFor="remember"
                                    className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    Remember me
                                </label>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button
                            type="submit"
                            className="w-full rounded-md hover:before:-translate-x-96"
                            disabled={processing}
                        >
                            Sign in
                        </Button>
                    </CardFooter>
                </Card>
            </form>
        </GuestLayout>
    );
}
