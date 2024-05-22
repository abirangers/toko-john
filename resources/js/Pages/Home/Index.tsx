import React from 'react'
import {cn} from '@/lib/utils'
import {ArrowRight, ShoppingCart} from "lucide-react";
import {
    NavigationMenu,
    NavigationMenuContent,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
    NavigationMenuTrigger,
} from "@/Components/ui/navigation-menu";

import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader, CardImage,
    CardTitle,
} from "@/Components/ui/card";

import {Button, buttonVariants} from "@/Components/ui/button";
import {Link} from "@inertiajs/react";


export default function Home() {
    return (
        <>
            <header className="sticky top-0 z-50 w-full border-b bg-background py-3">
                <nav className="flex justify-between mx-6">
                    {/*left navbar*/}
                    <div className="flex">
                        <div className="flex items-center gap-2 mr-8">
                            <img src="/images/logo.png" alt="logo penus" loading="lazy" width={24} height={24}/>
                            <h2 className="text-base font-bold">E-PENUS</h2>
                        </div>
                        <NavigationMenu>
                            <NavigationMenuList>
                                <NavigationMenuItem>
                                    <NavigationMenuTrigger>Lobby</NavigationMenuTrigger>
                                    <NavigationMenuContent>
                                        <ul className="grid gap-3 p-6 md:w-[400px] lg:w-[500px] lg:grid-cols-[.75fr_1fr]">
                                            <li className="row-span-3">
                                                <NavigationMenuLink asChild>
                                                    <a
                                                        className="flex h-full w-full select-none flex-col justify-end rounded-md bg-gradient-to-b from-muted/50 to-muted p-6 no-underline outline-none focus:shadow-md"
                                                        href="/"
                                                    >
                                                        <img src="/images/logo.png" alt="logo penus" loading="lazy"
                                                             width={24} height={24}/>
                                                        <div className="mb-2 mt-4 text-sm font-medium">
                                                            E-PENUS
                                                        </div>
                                                        <p className="text-sm leading-tight text-muted-foreground">
                                                            An open source ecommerce penus built with everything in
                                                            Laravel + Inertia
                                                        </p>
                                                    </a>
                                                </NavigationMenuLink>
                                            </li>
                                            <li>
                                                <NavigationMenuLink asChild>
                                                    <a
                                                        className={cn(
                                                            "block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                                        )}
                                                        href="/"
                                                    >
                                                        <div className="text-sm font-medium leading-none">Products</div>
                                                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                                            All the products we have to offer
                                                        </p>
                                                    </a>
                                                </NavigationMenuLink>
                                            </li>
                                            <li>
                                                <NavigationMenuLink asChild>
                                                    <a
                                                        className={cn(
                                                            "block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                                        )}
                                                        href="/"
                                                    >
                                                        <div className="text-sm font-medium leading-none">Categories
                                                        </div>
                                                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                                            See all categories we have
                                                        </p>
                                                    </a>
                                                </NavigationMenuLink>
                                            </li>
                                            {/*<ListItem href="/docs" title="Introduction">*/}
                                            {/*    Re-usable components built using Radix UI and Tailwind CSS.*/}
                                            {/*</ListItem>*/}
                                            {/*<ListItem href="/docs/installation" title="Installation">*/}
                                            {/*    How to install dependencies and structure your app.*/}
                                            {/*</ListItem>*/}
                                            {/*<ListItem href="/docs/primitives/typography" title="Typography">*/}
                                            {/*    Styles for headings, paragraphs, lists...etc*/}
                                            {/*</ListItem>*/}
                                        </ul>
                                    </NavigationMenuContent>
                                </NavigationMenuItem>
                                <NavigationMenuItem>
                                    <NavigationMenuTrigger>Categories</NavigationMenuTrigger>
                                    <NavigationMenuContent>
                                        <ul className="grid w-[400px] gap-3 p-4 md:w-[500px] md:grid-cols-2 lg:w-[600px] ">
                                            <li>
                                                <NavigationMenuLink asChild>
                                                    <a
                                                        className={cn(
                                                            "block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                                        )}
                                                        href="/"
                                                    >
                                                        <div className="text-sm font-medium leading-none">Clothing
                                                        </div>
                                                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                                            Explore the clothing category
                                                        </p>
                                                    </a>
                                                </NavigationMenuLink>
                                            </li>
                                            <li>
                                                <NavigationMenuLink asChild>
                                                    <a
                                                        className={cn(
                                                            "block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                                        )}
                                                        href="/"
                                                    >
                                                        <div className="text-sm font-medium leading-none">Accessories
                                                        </div>
                                                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                                            Explore the accessories category
                                                        </p>
                                                    </a>
                                                </NavigationMenuLink>
                                            </li>
                                            <li>
                                                <NavigationMenuLink asChild>
                                                    <a
                                                        className={cn(
                                                            "block select-none space-y-1 rounded-md p-3 leading-none no-underline outline-none transition-colors hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                                        )}
                                                        href="/"
                                                    >
                                                        <div className="text-sm font-medium leading-none">Shoes
                                                        </div>
                                                        <p className="line-clamp-2 text-sm leading-snug text-muted-foreground">
                                                            Explore the shoes category
                                                        </p>
                                                    </a>
                                                </NavigationMenuLink>
                                            </li>
                                        </ul>
                                    </NavigationMenuContent>
                                </NavigationMenuItem>
                            </NavigationMenuList>
                        </NavigationMenu>
                    </div>


                    {/* right navbar */}
                    <div className="flex items-center gap-2">
                        <Button size='sm' className='gap-x-1' variant='outline' aria-label='1-items-in-cart'>
                            <ShoppingCart className='w-4 h-4'/>
                            1
                        </Button>
                        <Button className={buttonVariants({
                            size: 'sm',
                        })}>
                            Sign In
                        </Button>
                    </div>
                </nav>
            </header>

            <section>
                <svg
                    className='absolute -z-10'
                    xmlns='http://www.w3.org/2000/svg'
                    viewBox='0 0 1440 320'
                >
                    <path
                        fill='#0d2a59'
                        fill-opacity='1'
                        d='M0,160L48,176C96,192,192,224,288,213.3C384,203,480,149,576,122.7C672,96,768,96,864,122.7C960,149,1056,203,1152,213.3C1248,224,1344,192,1392,176L1440,160L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z'
                    ></path>
                </svg>

                <div className="min-h-[512px] pt-16 max-w-7xl px-8 flex justify-center items-end mx-auto">
                    <div className="flex justify-between items-center w-full">
                        {/*left*/}
                        <div className="max-w-2xl">
                            <h1 className="text-6xl font-bold tracking-tighter leading-tight mb-11">An e-commerce penus
                                project built by <span className="text-secondary">iniabby</span></h1>
                            <p className="text-xl leading-8 font-normal text-muted-foreground mb-4">Buy and sell
                                skateboarding gears from independent brands
                                and stores around the world with ease</p>
                            <Button className="mr-4" size='sm'>Buy now</Button>
                            <Button size='sm' variant='outline'>Browse Products</Button>
                        </div>
                        {/*right*/}
                        <div>
                            <img src="/images/hero-image.png" alt="hero-image" loading="lazy"/>
                        </div>
                    </div>
                </div>
            </section>

            <section className="pt-24 px-8">
                <div className="mb-8">
                    <h2 className="text-6xl max-w-xs font-bold tracking-tighter leading-tight mb-4 text-secondary">Featured
                        Categories</h2>
                    <div className="flex justify-between">
                        <p className="text-lg leading-8 text-muted-foreground font-normal mb-4">Find the best
                            skateboarding gears from stores around the world</p>
                        <Link
                            className="text-base text-secondary flex gap-x-1 hover:translate-x-1 hover:text-secondary/80 transition-all"
                            href={''}>Shop the collection <ArrowRight className="w-6 h-6 text-secondary"/></Link>
                    </div>
                </div>

                <div className="flex gap-x-4">
                    <Link href={''}>
                        <Card className="w-[287.83px] h-[155.3px] mt-auto">
                            <CardHeader className="h-full justify-end">
                                <CardTitle className="text-secondary">Shoes</CardTitle>
                                <CardDescription>19 Product</CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>
                    <Link href={''}>
                        <Card className="w-[287.83px] h-[155.3px] mt-auto">
                            <CardHeader className="h-full justify-end">
                                <CardTitle className="text-secondary">Clothing</CardTitle>
                                <CardDescription>19 Product</CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>
                    <Link href={''}>
                        <Card className="w-[287.83px] h-[155.3px] mt-auto">
                            <CardHeader className="h-full justify-end">
                                <CardTitle className="text-secondary">Accessories</CardTitle>
                                <CardDescription>19 Product</CardDescription>
                            </CardHeader>
                        </Card>
                    </Link>
                </div>
            </section>
            <section className="pt-24 px-8">
                <div className="mb-8">
                    <h2 className="text-6xl font-bold tracking-tighter leading-tight mb-4 text-secondary">Popular
                        Products</h2>
                    <div className="flex justify-between">
                        <p className="text-lg leading-8 text-muted-foreground font-normal mb-4">Explore all products we
                            offer from around the world</p>
                        <Link
                            className="text-base text-secondary flex gap-x-1 hover:translate-x-1 hover:text-secondary/80 transition-all"
                            href={''}>Shop the collection <ArrowRight className="w-6 h-6 text-secondary"/></Link>
                    </div>
                </div>

                <div className="flex flex-wrap gap-5 mb-[72px]">
                    <Link href={''}
                          className="w-72 rounded-lg border bg-card text-card-foreground shadow-lg hover:shadow-xl p-4 transition-all">
                        <img src="/images/product1.jpg" alt="product1"
                             className="rounded-xl w-full h-60 object-cover mb-2"/>
                        <p className="text-sm leading-8 text-muted-foreground font-normal">Clothing</p>
                        <h2 className="text-lg font-semibold leading-tight mb-3">Baju Pramuka</h2>
                        <div className="flex justify-between items-center">
                            <h3 className="text-base font-semibold leading-tight text-secondary">Rp500.000</h3>
                            <div className="group">
                                <div
                                    className="p-2 rounded-full border shadow-md bg-secondary/10 group-hover:bg-secondary transition-all">
                                    <ShoppingCart
                                        className="text-secondary group-hover:text-primary-foreground transition-all"/>
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link href={''}
                          className="w-72 rounded-lg border bg-card text-card-foreground shadow-lg hover:shadow-xl p-4 transition-all">
                        <img src="/images/product1.jpg" alt="product1"
                             className="rounded-xl w-full h-60 object-cover mb-2"/>
                        <p className="text-sm leading-8 text-muted-foreground font-normal">Clothing</p>
                        <h2 className="text-lg font-semibold leading-tight mb-3">Baju Pramuka</h2>
                        <div className="flex justify-between items-center">
                            <h3 className="text-base font-semibold leading-tight text-secondary">Rp500.000</h3>
                            <div className="group">
                                <div
                                    className="p-2 rounded-full border shadow-md bg-secondary/10 group-hover:bg-secondary transition-all">
                                    <ShoppingCart
                                        className="text-secondary group-hover:text-primary-foreground transition-all"/>
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link href={''}
                          className="w-72 rounded-lg border bg-card text-card-foreground shadow-lg hover:shadow-xl p-4 transition-all">
                        <img src="/images/product1.jpg" alt="product1"
                             className="rounded-xl w-full h-60 object-cover mb-2"/>
                        <p className="text-sm leading-8 text-muted-foreground font-normal">Clothing</p>
                        <h2 className="text-lg font-semibold leading-tight mb-3">Baju Pramuka</h2>
                        <div className="flex justify-between items-center">
                            <h3 className="text-base font-semibold leading-tight text-secondary">Rp500.000</h3>
                            <div className="group">
                                <div
                                    className="p-2 rounded-full border shadow-md bg-secondary/10 group-hover:bg-secondary transition-all">
                                    <ShoppingCart
                                        className="text-secondary group-hover:text-primary-foreground transition-all"/>
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link href={''}
                          className="w-72 rounded-lg border bg-card text-card-foreground shadow-lg hover:shadow-xl p-4 transition-all">
                        <img src="/images/product1.jpg" alt="product1"
                             className="rounded-xl w-full h-60 object-cover mb-2"/>
                        <p className="text-sm leading-8 text-muted-foreground font-normal">Clothing</p>
                        <h2 className="text-lg font-semibold leading-tight mb-3">Baju Pramuka</h2>
                        <div className="flex justify-between items-center">
                            <h3 className="text-base font-semibold leading-tight text-secondary">Rp500.000</h3>
                            <div className="group">
                                <div
                                    className="p-2 rounded-full border shadow-md bg-secondary/10 group-hover:bg-secondary transition-all">
                                    <ShoppingCart
                                        className="text-secondary group-hover:text-primary-foreground transition-all"/>
                                </div>
                            </div>
                        </div>
                    </Link>
                    <Link href={''}
                          className="w-72 rounded-lg border bg-card text-card-foreground shadow-lg hover:shadow-xl p-4 transition-all">
                        <img src="/images/product1.jpg" alt="product1"
                             className="rounded-xl w-full h-60 object-cover mb-2"/>
                        <p className="text-sm leading-8 text-muted-foreground font-normal">Clothing</p>
                        <h2 className="text-lg font-semibold leading-tight mb-3">Baju Pramuka</h2>
                        <div className="flex justify-between items-center">
                            <h3 className="text-base font-semibold leading-tight text-secondary">Rp500.000</h3>
                            <div className="group">
                                <div
                                    className="p-2 rounded-full border shadow-md bg-secondary/10 group-hover:bg-secondary transition-all">
                                    <ShoppingCart
                                        className="text-secondary group-hover:text-primary-foreground transition-all"/>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <Link href={''}
                      className={cn(buttonVariants(), "mx-auto text-center flex w-fit hover:before:-translate-x-48")}>View
                    all products <ArrowRight/></Link>
            </section>

            <footer className="bg-white border-t mt-[72px]">
                <div className="mx-auto py-10">
                    <p className="text-center text-xs text-black">&copy; 2023 Ahmad Abby Ayyasi, Inc. All rights reserved</p>
                </div>
            </footer>
        </>
    )
}
