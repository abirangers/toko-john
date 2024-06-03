import { cn } from "@/lib/utils";
import { ArrowRight, ShoppingCart } from "lucide-react";

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardImage,
    CardTitle,
} from "@/Components/ui/card";

import { Button, buttonVariants } from "@/Components/ui/button";
import { Link } from "@inertiajs/react";
import Navbar from "@/Components/Navbar/Navbar";
import Footer from "@/Components/Footer/Footer";
import {Category, PageProps, Product} from "@/types";

interface HomeProps extends PageProps {
    categories: Category[];
    products: Product[];
}

const HomePage = ({ auth, categories, products }: HomeProps) => {
    return (
        <>
            {/*Navigation Bar*/}
            <Navbar user={auth.user} />

            {/*Hero*/}
            <section id="home">
                <svg
                    className="absolute -z-10"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 1440 320"
                >
                    <path
                        fill="#0d2a59"
                        fillOpacity="1"
                        d="M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,122.7C672,96,768,96,864,122.7C960,149,1056,203,1152,213.3C1248,224,1344,192,1392,176L1440,160L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"
                    ></path>
                </svg>

                <div className="min-h-[512px] flex items-end justify-center px-8 pt-24 mx-auto sm:pt-32 md:pt-16 max-w-7xl">
                    <div className="flex flex-col items-center justify-between w-full gap-x-9 md:flex-row">
                        {/*left*/}
                        <div className="w-full max-w-2xl">
                            <h1 className="mx-auto mb-4 text-3xl font-bold tracking-tighter text-center lg:leading-tight sm:leading-tight max-w-96 sm:max-w-max lg:text-6xl sm:text-5xl sm:text-left">
                                An e-commerce penus project built by{" "}
                                <span className="text-secondary">iniabby</span>
                            </h1>
                            <p className="mx-auto mb-4 text-sm font-normal text-center max-w-96 sm:max-w-max sm:text-left sm:leading-8 sm:text-xl text-muted-foreground">
                                Buy and sell skateboarding gears from
                                independent brands and stores around the world
                                with ease
                            </p>
                            <div className="flex justify-center gap-x-4 sm:justify-start">
                                <Link
                                    href="/products"
                                    className={cn(
                                        buttonVariants({
                                            size: "sm",
                                        })
                                    )}
                                >
                                    Buy now
                                </Link>
                                <Link
                                    href="/products"
                                    className={cn(
                                        buttonVariants({
                                            size: "sm",
                                            variant: "outline",
                                        })
                                    )}
                                >
                                    Browse Products
                                </Link>
                            </div>
                        </div>
                        {/*right*/}
                        <div className="w-[332px]">
                            <img
                                src="/images/hero-image.png"
                                alt="hero-image"
                                loading="lazy"
                                className="h-full"
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/*Featured Categories*/}
            <section id="category" className="px-8 pt-24">
                <div className="mb-8">
                    <h2 className="mb-3 text-3xl font-bold leading-tight tracking-normal sm:mb-4 md:max-w-sm sm:text-4xl md:text-5xl text-secondary">
                        Featured Categories
                    </h2>
                    <div className="flex justify-between">
                        <p className="text-base font-normal sm:text-lg md:mb-4 text-muted-foreground">
                            Find the best skateboarding gears from stores around
                            the world
                        </p>
                        <Link
                            className="hidden text-base transition-all md:flex text-secondary gap-x-1 hover:translate-x-1 hover:text-secondary/80"
                            href={""}
                        >
                            Shop the collection{" "}
                            <ArrowRight className="w-6 h-6 text-secondary" />
                        </Link>
                    </div>
                </div>

                <div className="grid gap-4 sm:grid-flow-col sm:grid-rows-2 md:grid-rows-1">
                    {categories.map((category, index) => (
                        <Link href={""} className="group">
                            <Card className="transition-all group-hover:bg-secondary">
                                <CardHeader>
                                    <CardImage
                                        src={`/images/${(category.name).toLowerCase()}.svg`}
                                        className="w-8 h-8"
                                    />
                                </CardHeader>
                                <CardContent>
                                    <CardTitle className="text-secondary mb-[6px] text-2xl font-semibold tracking-tight group-hover:text-primary-foreground">
                                        {category.name}
                                    </CardTitle>
                                    <CardDescription className="group-hover:text-primary-foreground">
                                        {category.products.length} Product
                                    </CardDescription>
                                </CardContent>
                            </Card>
                        </Link>
                    ))}
                </div>
            </section>

            {/*Popular Products*/}
            <section id="popularProduct" className="px-8 pt-24">
                <div className="mb-8">
                    <h2 className="mb-3 text-3xl font-bold leading-tight tracking-normal sm:mb-4 sm:text-4xl md:text-5xl text-secondary">
                        Popular Products
                    </h2>
                    <div className="flex justify-between">
                        <p className="text-base font-normal sm:text-lg md:mb-4 text-muted-foreground">
                            Explore all products we offer from around the world
                        </p>
                        <Link
                            className="hidden text-base transition-all md:flex text-secondary gap-x-1 hover:translate-x-1 hover:text-secondary/80"
                            href={""}
                        >
                            Shop the collection{" "}
                            <ArrowRight className="w-6 h-6 text-secondary" />
                        </Link>
                    </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-[72px]">
                    {products.map((product, index) => (
                        <Link href={""} className="p-4 transition-all border rounded-lg shadow-lg bg-card text-card-foreground hover:shadow-xl">
                            <img
                                src={`/storage/${product.image}`}
                                alt="product1"
                                className="object-cover w-full mb-2 rounded-xl h-60"
                            />
                            <p className="text-sm font-normal leading-8 text-muted-foreground">
                                {product.category.name}
                            </p>
                            <h2 className="mb-3 text-lg font-semibold leading-tight">
                                {product.title}
                            </h2>
                            <div className="flex items-center justify-between">
                                <h3 className="text-base font-semibold leading-tight text-secondary">
                                    {Number(product.price).toLocaleString('id-ID', {style: 'currency', currency: 'IDR',minimumFractionDigits: 0,
                                    maximumFractionDigits: 0})}
                                </h3>
                                <div className="group">
                                    <div className="p-2 transition-all border rounded-full shadow-md bg-secondary/10 group-hover:bg-secondary">
                                        <ShoppingCart className="transition-all text-secondary group-hover:text-primary-foreground" />
                                    </div>
                                </div>
                            </div>
                        </Link>
                    ))}

                </div>

                <Link
                    href={"/products"}
                    className={cn(
                        buttonVariants(),
                        "mx-auto text-center flex w-fit hover:before:-translate-x-48"
                    )}
                >
                    View all products <ArrowRight />
                </Link>
            </section>

            {/*Footer*/}
            <Footer />
        </>
    );
};

export default HomePage;
