def cambio_minimo(monedas, monto):
    monedas.sort(reverse=True)
    resultado = []
    monto_centavos = int(round(monto * 100))
    monedas_centavos = [int(m * 100) for m in monedas]  
    for moneda in monedas_centavos:
        while monto_centavos >= moneda:
            monto_centavos -= moneda
            resultado.append(moneda / 100) 
    if monto_centavos != 0:
        return None  
    return resultado
monedas_mex = [20, 10, 5, 2, 1, 0.5]
while True:
    try:
        monto = float(input("Introduce el monto a cambiar (ejemplo 137.50): "))
        if monto < 0:
            print("Por favor, introduce un monto positivo.")
            continue
        break
    except ValueError:
        print("Entrada inválida. Por favor, introduce un número válido.")
cambio = cambio_minimo(monedas_mex, monto)
if cambio:
    print(f"\nCambio para ${monto} con monedas mexicanas {monedas_mex}:")
    print(cambio)
    print(f"Número de monedas usadas: {len(cambio)}")
else:
    print("No es posible dar cambio exacto con las monedas disponibles.")
