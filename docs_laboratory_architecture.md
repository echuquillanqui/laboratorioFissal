# Arquitectura de Módulo de Laboratorio Clínico

## Orden recomendado de desarrollo
1. Catálogos base: áreas, pruebas, opciones.
2. Perfiles de laboratorio.
3. Paquetes mixtos (test + perfil).
4. Órdenes individuales y expansión de ítems.
5. Registro de resultados dinámicos.
6. Órdenes masivas con transacciones.
7. Seguridad: policies, roles y auditoría.
8. Reportes/impresión.

## Diagrama lógico (texto)
- laboratory_areas 1---* laboratory_tests
- laboratory_tests 1---* laboratory_test_options
- laboratory_profiles *---* laboratory_tests (laboratory_profile_test)
- laboratory_packages 1---* laboratory_package_items (tipo_item/reference_id polimórfico manual)
- laboratory_orders *---1 patients
- laboratory_orders *---1 users
- laboratory_orders 1---* laboratory_order_items
- laboratory_order_items *---1 laboratory_tests
- laboratory_order_items 1---1 laboratory_results

## Flujo del sistema
1. Recepción crea orden seleccionando pruebas/perfiles/paquetes.
2. Servicio expande perfiles/paquetes, elimina duplicados y guarda ítems.
3. Laboratorio registra resultados según tipo_dato.
4. Validación médica cambia estado a validado/entregado.
5. Auditoría guarda cambios clave de estado/resultados.

## Estructura sugerida
- app/Models/Laboratory/*
- app/Services/Laboratory/*
- app/Livewire/Laboratory/*
- app/Policies/Laboratory/*
- database/migrations/*laboratory*
- resources/views/livewire/laboratory/*

## Mejoras futuras
- Integración HL7/ASTM con equipos automatizados.
- PDF de resultados y etiquetas de muestra.
- Exportación Excel/CSV por fecha/área.
- Historial clínico longitudinal por paciente.
- Firma digital de validación.
- Dashboard KPI (TAT, críticos, productividad).
- Control de calidad interno/externo con reglas Westgard.
