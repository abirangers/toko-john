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
import { Head, Link, useForm } from "@inertiajs/react";
import GuestLayout from "@/Layouts/GuestLayout";
import { ChangeEvent, FormEvent, FormEventHandler, useEffect } from "react";
import InputError from "@/Components/InputError";
import { Checkbox } from "@/Components/ui/checkbox";
import Logo from "@/Components/Navbar/Logo";

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

            <div className="w-full">
                <div className="flex min-h-screen">
                    <div
                        className="relative w-1/2 p-8 bg-center bg-cover"
                        style={{
                            backgroundImage: "url('/images/auth-image.jpg')",
                        }}
                    >
                        <div className="absolute inset-0 bg-gradient-to-t from-white to-transparent"></div>
                        <Link
                            href="/"
                            className="relative flex items-center gap-2 mb-8"
                        >
                            <img
                                src="/images/logo.jpeg"
                                alt="logo"
                                loading="lazy"
                                width={32}
                                height={32}
                            />
                            <h2 className="text-xl font-bold text-secondary">
                                JohnP
                            </h2>
                        </Link>
                    </div>
                    <form onSubmit={submit} className="w-1/2 my-auto">
                        <Card className="w-full max-w-md mx-auto border-none shadow-none">
                            <CardHeader>
                                <CardTitle className="text-2xl text-center text-secondary">
                                    Login
                                </CardTitle>
                                <CardDescription className="text-center">
                                    Enter your email below to login to your
                                    account.
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
                                    <Link
                                        href="/forgot-password"
                                        className="text-sm text-right text-blue-600"
                                    >
                                        Forgot your password?
                                    </Link>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <Checkbox
                                        name="remember"
                                        checked={data.remember}
                                        onCheckedChange={(checked: boolean) => {
                                            setData("remember", checked);
                                        }}
                                    />
                                    <label
                                        htmlFor="remember"
                                        className="text-sm font-medium leading-none"
                                    >
                                        Remember me
                                    </label>
                                </div>
                            </CardContent>
                            <CardFooter className="flex flex-col space-y-4">
                                <Button
                                    type="submit"
                                    className="w-full text-white hover:before:-translate-x-[426px]"
                                    disabled={processing}
                                >
                                    Login
                                </Button>
                                <div className="text-center">
                                    <span className="text-sm">
                                        Don't have an account?{" "}
                                        <Link
                                            href="/register"
                                            className="text-blue-600"
                                        >
                                            Sign up
                                        </Link>
                                    </span>
                                </div>
                            </CardFooter>
                        </Card>
                    </form>
                </div>
            </div>
        </GuestLayout>
    );
}
