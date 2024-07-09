import { useEffect, FormEventHandler } from "react";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import GuestLayout from "@/Layouts/GuestLayout";
import InputError from "@/Components/InputError";
import { Head, Link, useForm } from "@inertiajs/react";
import { Label } from "@/Components/ui/label";
import { Input } from "@/Components/ui/input";
import { Button } from "@/Components/ui/button";
import { Checkbox } from "@/Components/ui/checkbox";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    useEffect(() => {
        return () => {
            reset("password", "password_confirmation");
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route("register"));
    };

    return (
        <GuestLayout>
            <Head title="Register" />

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
                                    Register
                                </CardTitle>
                                <CardDescription className="text-center">
                                    Create your account by filling in the
                                    details below.
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="space-y-6">
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Name</Label>
                                    <Input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value={data.name}
                                        className="block w-full mt-1"
                                        autoComplete="name"
                                        onChange={(e) =>
                                            setData("name", e.target.value)
                                        }
                                    />
                                    <InputError message={errors.name} />
                                </div>
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
                                <div className="grid gap-2">
                                    <Label htmlFor="password_confirmation">Password Confirmation</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        value={data.password_confirmation}
                                        className="block w-full mt-1"
                                        autoComplete="current-password"
                                        onChange={(e) =>
                                            setData("password_confirmation", e.target.value)
                                        }
                                    />
                                    <InputError message={errors.password} />
                                </div>
                            </CardContent>
                            <CardFooter className="flex flex-col space-y-4">
                                <Button
                                    type="submit"
                                    className="w-full text-white"
                                    disabled={processing}
                                >
                                    Register
                                </Button>
                                <div className="text-center">
                                    <span className="text-sm">
                                        Already have an account?{" "}
                                        <Link
                                            href="/login"
                                            className="text-blue-600"
                                        >
                                            Sign in
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
