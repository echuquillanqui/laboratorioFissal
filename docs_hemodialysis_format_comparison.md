# Comparación de formatos físicos de hemodiálisis con tablas actuales

## Alcance

Este documento compara los formatos físicos adjuntos de la Unidad de Hemodiálisis con las tablas actuales del módulo de hemodiálisis y pacientes. La comparación se basa en los campos legibles de las imágenes enviadas y en las migraciones vigentes del repositorio.

## Tablas revisadas

| Tabla | Uso actual | Observación general |
|---|---|---|
| `patients` | Filiación base del paciente. | Cubre datos mínimos: nombres, DNI, fecha de nacimiento, edad, sexo, régimen, historia clínica, dirección y teléfono. No cubre estado civil, grado de instrucción, procedencia, ocupación ni contacto familiar estructurado. |
| `hemodialysis_admissions` | Historia clínica de ingreso a hemodiálisis. | Cubre ingreso, procedencia, diagnóstico renal, etiología, antecedentes, comorbilidades, acceso vascular inicial, indicación y observaciones. Varios campos del formato físico quedan agrupados en textos generales. |
| `hemodialysis_medical_evaluations` | Evaluación médica de ingreso. | Cubre motivo, examen físico, diagnósticos, plan, riesgos e indicaciones como textos generales. No estructura signos vitales, serología, vacunas, acceso vascular, diagnóstico renal ni alta. |
| `hemodialysis_sessions` | Ficha por sesión de hemodiálisis. | Cubre número y fecha de sesión, tiempos, pesos, acceso, anticoagulación, flujos, dializador, UF, complicaciones, prescripción, tolerancia y observaciones. Faltan varios campos operativos del formato de sesión. |
| `hemodialysis_nursing_notes` | Notas SOAPIE de enfermería. | Alineación alta con el formato físico de notas de enfermería, salvo metadatos administrativos de cama, seguro y servicio que se resuelven parcialmente por paciente/sesión. |
| `hemodialysis_laboratory_monitors` y `hemodialysis_laboratory_monitor_results` | Monitoreo longitudinal de laboratorio. | La tabla de resultados es flexible y permite capturar cualquier examen como filas. Faltan columnas de fecha por cada control y datos de cabecera como grupo sanguíneo/factor Rh si se desean estructurados. |

## Resumen por formato físico

| Formato físico | Tabla principal actual | Cobertura | Brechas principales |
|---|---|---:|---|
| Historia clínica de ingreso a hemodiálisis | `patients` + `hemodialysis_admissions` | Parcial | Datos de filiación ampliados, contacto, servicio de origen, enfermedad actual, funciones biológicas, antecedentes personales por patología, antecedentes familiares, alergias y biopsia renal. |
| Evaluación médica / examen físico de ingreso y alta | `hemodialysis_medical_evaluations` + `hemodialysis_admissions` | Parcial baja | Signos vitales, examen físico por sistemas, acceso vascular detallado, serología viral, vacunas, diagnóstico estructurado, diagnósticos complementarios, consideraciones/pendientes/condición al alta. |
| Monitoreo de laboratorio de pacientes en hemodiálisis | `hemodialysis_laboratory_monitors` + `hemodialysis_laboratory_monitor_results` | Parcial alta | Catálogo inicial de pruebas debe ampliarse; falta persistir categoría, fecha/columna de control y grupo/Rh como cabecera estructurada. |
| Ficha de hemodiálisis por sesión | `hemodialysis_sessions` | Parcial | Evaluación clínica completa, alergia a medicamentos, parámetros de prescripción detallados, grado de dependencia, transfusiones, temperatura inicial/final, hoja horaria intradiálisis, medicamentos administrados y firmas. |
| Notas de enfermería de hemodiálisis | `hemodialysis_nursing_notes` | Alta | El SOAPIE está cubierto. Faltan campos administrativos inferiores del papel si deben imprimirse/capturarse de forma independiente: servicio, cama, seguro y fecha de ingreso a terapia HD. |

## Detalle de comparación por formato

### 1. Historia clínica de ingreso a hemodiálisis

Campos del formato físico:

- Financiad(or): SIS, SaludPol, EsSalud, particular.
- Fecha de ingreso a HD actual, historia clínica y código SIS/SaludPol.
- Filiación: nombre, DNI/carné de extranjería, fecha de nacimiento, teléfono, edad, años, sexo, estado civil, grado de instrucción, procedencia, dirección y ocupación.
- Persona de contacto: nombre, relación, DNI y teléfono.
- Servicio de origen: UTS, tópico 1, tópico 2, observación, UCI, UCIN, UPA, medicina, cirugía, ginecología, pediatría, emergencia, clínica externa, URPA y cama.
- Enfermedad actual: tiempo de enfermedad, inicio, curso y relato cronológico.
- Funciones biológicas: apetito, sed, heces, sueño y diuresis al ingreso.
- Antecedentes personales: diabetes mellitus, hipertensión, enfermedad cardiovascular, glomerulonefritis, vasculitis, LES, uropatía obstructiva, litiasis urinaria, quistes/ERPQ, tuberculosis, ERC, cirugías previas, obesidad, tabaquismo, alcoholismo, sedentarismo, transfusiones y otros.
- Antecedentes familiares relevantes, alergias y biopsia renal.

Cobertura actual:

- `patients` cubre régimen, nombres, DNI, fecha de nacimiento, edad, sexo, historia clínica, dirección y teléfono.
- `hemodialysis_admissions` cubre fecha de ingreso, procedencia, diagnóstico renal, etiología, antecedentes, comorbilidades, acceso vascular inicial, indicación HD y observaciones.

Brechas recomendadas:

- Agregar campos estructurados de contacto de emergencia o una tabla `patient_contacts`.
- Agregar datos administrativos de ingreso: código SIS/SaludPol, servicio de origen, cama y ocupación.
- Separar antecedentes personales en JSON o columnas booleanas con año/medicación previa cuando se necesite reporte clínico.
- Agregar alergias y biopsia renal como campos estructurados si se requiere trazabilidad independiente.

### 2. Evaluación médica / examen físico de ingreso y alta

Campos del formato físico:

- Signos y antropometría: PA, FC, FR, saturación, fiebre/temperatura, peso y talla.
- Examen físico por secciones: aspecto general, piel, TCSC, respiratorio, cardiovascular, abdomen, génito urinario, neurológico y estado nutricional.
- Acceso vascular: fecha de acceso actual, tipo, pulso, localización, lado derecho/izquierdo, otras terapias previas, otros accesos vasculares, fecha de creación y causa de pérdida/cambio.
- Serología viral al ingreso/reingreso: HBsAg, VIH, Anti-HBc, VHC, Anti-HBs y RPR/VDRL, con ingreso sin serologías.
- Vacunación hepatitis B y otras vacunas.
- Diagnóstico: ERC con estadio/condición, lesión renal aguda con códigos y etiología.
- Motivo de ingreso a hemodiálisis, diagnósticos complementarios y firmas.
- Alta: fecha de alta, sale de TRR, continúa en TRR, retiro voluntario, fallece/motivo, prueba áurica, seguimiento en unidad, pasa a clínica tercerizada, pendientes al alta, peso seco al alta y diuresis residual al alta.

Cobertura actual:

- `hemodialysis_medical_evaluations` permite capturar motivo de ingreso, examen físico, diagnósticos, plan de tratamiento, riesgos e indicaciones médicas en campos de texto.
- `hemodialysis_admissions` cubre parcialmente diagnóstico renal, etiología y acceso vascular inicial.

Brechas recomendadas:

- Crear campos estructurados de signos vitales y examen físico por sistema, o guardar un JSON clínico validado.
- Estructurar acceso vascular para no depender de texto libre.
- Incorporar serología y vacunación como secciones específicas.
- Crear una sección o tabla de alta de hemodiálisis/hospitalización si el flujo requiere cierre clínico con pendientes y condición al alta.

### 3. Monitoreo de laboratorio de pacientes en hemodiálisis

Campos del formato físico:

- Cabecera: nombre, historia clínica, fecha de ingreso a HD actual, grupo sanguíneo y factor Rh.
- Pruebas por categorías: función renal, hemograma/coagulación/reactantes, perfil de anemia, medio interno/oxigenación, metabolismo mineral/óseo, función hepática, perfil lipídico, autoinmunidad, examen de orina y diuresis diaria.
- Grilla longitudinal con múltiples columnas de control.

Cobertura actual:

- `hemodialysis_laboratory_monitors` cubre paciente, sesión, orden de laboratorio, fecha de muestra, observación y estado.
- `hemodialysis_laboratory_monitor_results` permite registrar filas de prueba con nombre, valor, unidad, referencia y alerta.

Brechas recomendadas:

- Ampliar el catálogo inicial de pruebas para coincidir con el papel.
- Agregar `categoria` y `orden` a los resultados si se quiere imprimir agrupado como el formato físico.
- Si se desea una sola hoja longitudinal, usar múltiples monitores por fecha y agruparlos por paciente al imprimir, o crear una vista PDF que pivote los resultados por fecha.
- Registrar grupo sanguíneo y factor Rh como datos del paciente o cabecera clínica si se consideran obligatorios.

### 4. Ficha de hemodiálisis por sesión

Campos del formato físico:

- Cabecera: fecha, sesión, nombre, edad, sexo, historia clínica, DNI, SIS, servicio, cama y teléfono.
- Evaluación clínica: PA, FC, FR, saturación, peso seco, diuresis y alergia a medicamentos.
- Prescripción: técnico, frecuencia, acceso, heparina, filtro, membrana, QB, QD, tiempo, sodio, perfil sodio, temperatura de líquido de diálisis, UFT, UF aislada, perfil UF, UF efectivo y otras indicaciones.
- Grado de dependencia, grupo/factor Rh, transfusiones, temperatura inicial/final, peso inicial/final.
- Tabla horaria: hora, PA, PAM, FC, SaO, UF, sodio, QB, RA, RV, PTM, observaciones y laboratorio.
- Cierre: UF efectivo, aspecto de filtro, EPO, hierro, vitamina B12 y firmas de enfermería.

Cobertura actual:

- `hemodialysis_sessions` cubre número/fecha de sesión, hora de inicio/fin, peso pre/post, acceso vascular, tipo de catéter, horas HD, UF, anticoagulación, flujo sanguíneo, flujo dializado, dializador, complicaciones, prescripción médica, tolerancia y observaciones.

Brechas recomendadas:

- Agregar campos de prescripción específicos: técnico, frecuencia, filtro, membrana, QB, QD, sodio, perfiles, temperatura de baño y UF aislada.
- Agregar signos vitales prediálisis y alergias de sesión o vincularlos a una evaluación clínica estructurada.
- Crear tabla hija `hemodialysis_session_vital_controls` para la grilla horaria intradiálisis.
- Agregar administración de EPO/hierro/vitamina B12 y firmas si se usará para cierre operacional.

### 5. Notas de enfermería de hemodiálisis

Campos del formato físico:

- Fecha de nota.
- Tabla SOAPIE: hora, S, O, A, P, I, E, nota de enfermería y firma.
- Pie: nombre del paciente, servicio, número de cama, fecha de ingreso a terapia HD, historia clínica y número de seguro.

Cobertura actual:

- `hemodialysis_nursing_notes` cubre fecha, paciente, sesión, enfermera y los campos SOAPIE: subjetivo, objetivo, análisis, plan, intervención y evaluación.

Brechas recomendadas:

- Agregar firma/código de responsable si no basta `nurse_id`.
- Resolver servicio, cama y número de seguro desde ingreso/admisión o agregarlos al modelo si deben variar por nota.

## Priorización sugerida

1. **Monitoreo de laboratorio:** ampliar catálogo y salida PDF longitudinal porque la tabla actual ya soporta resultados flexibles.
2. **Ficha de sesión:** crear tabla hija para controles horarios y completar campos de prescripción.
3. **Evaluación médica:** estructurar signos vitales, acceso vascular, serología, vacunación y alta.
4. **Historia de ingreso:** ampliar filiación/contacto/servicio de origen y antecedentes estructurados.
5. **Notas de enfermería:** mantener SOAPIE actual y agregar solo firma/metadatos si el hospital lo exige.
