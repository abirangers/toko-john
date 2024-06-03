import * as React from 'react';
import { Button } from '../ui/button';
import { ShoppingCart } from 'lucide-react';

const CartIcon = () => {
  return (
      <Button
          size="sm"
          className="gap-x-1"
          variant="outline"
          aria-label="1-items-in-cart"
      >
          <ShoppingCart className="w-4 h-4" />1
      </Button>
  );
};

export default CartIcon;