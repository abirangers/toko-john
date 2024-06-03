import Navbar from "@/Components/Navbar/Navbar";
import { Button } from "@/Components/ui/button";
import { ShoppingCart } from "lucide-react";
import * as React from "react";
import {PageProps} from "@/types";

const ProductDetail = ({auth}: PageProps) => {
    return (
        <>
            <Navbar user={auth.user}/>
            <section className="px-8 mt-4">
                <div className="flex justify-center gap-x-8">
                    {/* left */}
                    <div className="max-w-xl h-[496px] rounded-sm">
                        <img
                            src="/images/product1.jpg"
                            alt="e"
                            className="object-cover h-full rounded-sm"
                        />
                    </div>
                    {/* right */}
                    <div className="w-1/2 pt-5">
                        <div className="border-b">
                            <h1 className="mb-2 text-4xl font-bold leading-tight tracking-tighter text-secondary">
                                Biji air
                            </h1>
                            <h2 className="mb-3 text-2xl font-semibold leading-tight tracking-tighter">
                                Rp500.000
                            </h2>
                            <p className="mb-4 text-sm font-normal text-muted-foreground">
                                Clothing
                            </p>
                        </div>
                        <div className="pt-4">
                            <h3 className="mb-2 text-base font-semibold leading-tight">
                                Description:
                            </h3>
                            <p className="text-base font-normal text-muted-foreground">
                                Lorem ipsum dolor sit amet consectetur
                                adipisicing elit. Molestiae laboriosam ex,
                                quidem et nihil sapiente sint quo sit, in fugiat
                                commodi ullam odit tempore sed impedit quos
                                vero, dolorem eum.
                            </p>
                        </div>
                        <Button className="mt-4 gap-x-2">
                            Add to cart <ShoppingCart />
                        </Button>
                    </div>
                </div>
            </section>
        </>
    );
};

export default ProductDetail;
