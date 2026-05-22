document.addEventListener('DOMContentLoaded', () => {
  const updateText = (selector, value) => {
    document.querySelectorAll(selector).forEach((element) => {
      element.textContent = value;
    });
  };

  const setLoading = (form, isLoading) => {
    form.classList.toggle('is-updating', isLoading);
    form.querySelectorAll('input, button').forEach((control) => {
      control.disabled = isLoading;
    });
  };

  const updateCartTotals = (data) => {
    updateText('[data-cart-count]', data.cart_count);
    updateText('[data-cart-count-label]', `${data.cart_count} ${data.cart_count === 1 ? 'producto' : 'productos'}`);
    updateText('[data-cart-total]', data.total_formatted);
  };

  const updateCart = (data) => {
    updateCartTotals(data);

    document.querySelectorAll(`[data-cart-item="${data.item.id}"] [data-cart-item-subtotal]`).forEach((element) => {
      element.textContent = data.item.subtotal_formatted;
    });

    document.querySelectorAll(`[data-cart-summary-item="${data.item.id}"]`).forEach((row) => {
      const label = row.querySelector('[data-cart-summary-label]');
      const subtotal = row.querySelector('[data-cart-summary-subtotal]');

      if (label) {
        label.textContent = label.textContent.replace(/\u00d7\s*\d+$/, `\u00d7 ${data.item.quantity}`);
      }

      if (subtotal) {
        subtotal.textContent = data.item.subtotal_formatted;
      }
    });
  };

  const removeCartItem = (data) => {
    updateCartTotals(data);

    document.querySelectorAll(`[data-cart-item="${data.removed_item_id}"]`).forEach((element) => {
      element.remove();
    });

    document.querySelectorAll(`[data-cart-summary-item="${data.removed_item_id}"]`).forEach((element) => {
      element.remove();
    });

    if (data.is_empty) {
      document.querySelectorAll('[data-cart-preview-list], .cart-preview-total').forEach((element) => {
        element.remove();
      });

      document.querySelectorAll('[data-cart-empty]').forEach((element) => {
        element.hidden = false;
      });
    }
  };

  const submitAjaxForm = async (form, onSuccess) => {
    if (form.classList.contains('is-updating')) {
      return;
    }

    const formData = new FormData(form);
    setLoading(form, true);

    try {
      const response = await fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
          Accept: 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      });

      if (!response.ok) {
        form.submit();
        return;
      }

      onSuccess(await response.json());
    } catch (error) {
      form.submit();
    } finally {
      setLoading(form, false);
    }
  };

  document.querySelectorAll('.js-cart-update-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      if (event.submitter && event.submitter.matches('.cart-update-button')) {
        return;
      }

      event.preventDefault();
      submitAjaxForm(form, updateCart);
    });

    form.querySelectorAll('[data-cart-quantity-input]').forEach((input) => {
      input.addEventListener('change', () => submitAjaxForm(form, updateCart));
    });

    form.querySelectorAll('[data-cart-step]').forEach((button) => {
      button.addEventListener('click', () => {
        const input = form.querySelector('[data-cart-quantity-input]');

        if (!input) {
          return;
        }

        Number(button.dataset.cartStep) > 0 ? input.stepUp() : input.stepDown();
        submitAjaxForm(form, updateCart);
      });
    });
  });

  document.querySelectorAll('.js-cart-remove-form').forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      submitAjaxForm(form, removeCartItem);
    });
  });
});
