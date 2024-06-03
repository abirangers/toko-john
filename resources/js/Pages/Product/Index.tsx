import Navbar from "@/Components/Navbar/Navbar";
import { Button, buttonVariants } from "@/Components/ui/button";
import { cn } from "@/lib/utils";
import { PageProps } from "@/types";
import { Link } from "@inertiajs/react";
import { ArrowRight, ShoppingCart, Plus } from "lucide-react";
import React from "react";

const ProductPage = ({ auth }: PageProps) => {
    return (
        <>
            <Navbar user={auth.user} />
            <section className="px-8 pt-10">
                <div className="mb-6">
                    <h2 className="mb-1 text-3xl font-bold tracking-tighter text-secondary">
                        Products (42)
                    </h2>
                    <p className="mb-4 text-sm font-normal text-muted-foreground">
                        Explore all products we offer from around the world
                    </p>
                    <Button className="mr-4 mt-7 gap-x-2">
                        Filters <Plus />
                    </Button>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-[72px]">
                    <Link
                        href={""}
                        className="p-4 transition-all border rounded-lg shadow-lg bg-card text-card-foreground hover:shadow-xl"
                    >
                        <img
                            src="/images/product1.jpg"
                            alt="product1"
                            className="object-cover w-full mb-2 rounded-xl h-60"
                        />
                        <p className="text-sm font-normal leading-8 text-muted-foreground">
                            Clothing
                        </p>
                        <h2 className="mb-3 text-lg font-semibold leading-tight">
                            Baju Pramuka
                        </h2>
                        <div className="flex items-center justify-between">
                            <h3 className="text-base font-semibold leading-tight text-secondary">
                                Rp500.000
                            </h3>
                            <div className="group">
                                <div className="p-2 transition-all border rounded-full shadow-md bg-secondary/10 group-hover:bg-secondary">
                                    <ShoppingCart className="transition-all text-secondary group-hover:text-primary-foreground" />
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link
                        href={""}
                        className="p-4 transition-all border rounded-lg shadow-lg bg-card text-card-foreground hover:shadow-xl"
                    >
                        <img
                            src="/images/product1.jpg"
                            alt="product1"
                            className="object-cover w-full mb-2 rounded-xl h-60"
                        />
                        <p className="text-sm font-normal leading-8 text-muted-foreground">
                            Clothing
                        </p>
                        <h2 className="mb-3 text-lg font-semibold leading-tight">
                            Baju Pramuka
                        </h2>
                        <div className="flex items-center justify-between">
                            <h3 className="text-base font-semibold leading-tight text-secondary">
                                Rp500.000
                            </h3>
                            <div className="group">
                                <div className="p-2 transition-all border rounded-full shadow-md bg-secondary/10 group-hover:bg-secondary">
                                    <ShoppingCart className="transition-all text-secondary group-hover:text-primary-foreground" />
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link
                        href={""}
                        className="p-4 transition-all border rounded-lg shadow-lg bg-card text-card-foreground hover:shadow-xl"
                    >
                        <img
                            src="/images/product1.jpg"
                            alt="product1"
                            className="object-cover w-full mb-2 rounded-xl h-60"
                        />
                        <p className="text-sm font-normal leading-8 text-muted-foreground">
                            Clothing
                        </p>
                        <h2 className="mb-3 text-lg font-semibold leading-tight">
                            Baju Pramuka
                        </h2>
                        <div className="flex items-center justify-between">
                            <h3 className="text-base font-semibold leading-tight text-secondary">
                                Rp500.000
                            </h3>
                            <div className="group">
                                <div className="p-2 transition-all border rounded-full shadow-md bg-secondary/10 group-hover:bg-secondary">
                                    <ShoppingCart className="transition-all text-secondary group-hover:text-primary-foreground" />
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link
                        href={""}
                        className="p-4 transition-all border rounded-lg shadow-lg bg-card text-card-foreground hover:shadow-xl"
                    >
                        <img
                            src="/images/product1.jpg"
                            alt="product1"
                            className="object-cover w-full mb-2 rounded-xl h-60"
                        />
                        <p className="text-sm font-normal leading-8 text-muted-foreground">
                            Clothing
                        </p>
                        <h2 className="mb-3 text-lg font-semibold leading-tight">
                            Baju Pramuka
                        </h2>
                        <div className="flex items-center justify-between">
                            <h3 className="text-base font-semibold leading-tight text-secondary">
                                Rp500.000
                            </h3>
                            <div className="group">
                                <div className="p-2 transition-all border rounded-full shadow-md bg-secondary/10 group-hover:bg-secondary">
                                    <ShoppingCart className="transition-all text-secondary group-hover:text-primary-foreground" />
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>
            </section>
        </>
    );
};

export default ProductPage;
