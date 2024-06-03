import { Link } from '@inertiajs/react';
import * as React from 'react';

const Logo = () => {
  return (
      <Link href="/" className="flex items-center gap-2 mr-8">
          <img
              src="/images/logo.png"
              alt="logo penus"
              loading="lazy"
              width={24}
              height={24}
          />
          <h2 className="text-base font-bold">E-PENUS</h2>
      </Link>
  );
};

export default Logo;