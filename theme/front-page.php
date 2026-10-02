<?php
/**
 * The single page of the site.
 *
 * @package francoruiz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>


<header class="top">
  <div class="wrap">
    <a class="brand" href="#inicio">Franco Ruiz</a>
    <nav aria-label="Principal">
      <a href="#sobre-mi">Sobre mí</a>
      <a href="#para-quien">Para quién es</a>
      <a href="#como-trabajo">Cómo trabajo</a>
      <a class="nav-cta" href="#formulario">Solicitar entrevista</a>
    </nav>
  </div>
</header>

<main id="inicio">

  <section class="hero">
    <div class="wrap hero-grid">
      <div class="hero-head rise">
        <div class="rule"></div>
        <h1>Asesor Personal en Desarrollo Humano</h1>
        <p class="hero-intro">Acompaño a líderes, artistas y ejecutivos a pensar con claridad y vivir con propósito.</p>
      </div>
      <div class="answer rise">
        <label for="hero-answer">¿En qué área de tu vida necesitás mayor claridad?</label>
        <textarea id="hero-answer" rows="1" placeholder="Escribila acá, con tus palabras."></textarea>
        <div class="answer-row">
          <button type="button" class="btn btn-brass" id="hero-continue">Seguir con mi aplicación</button>
          <p class="answer-note">Nada se envía hasta que completes la aplicación.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="block" id="sobre-mi">
    <div class="wrap about">
      <div class="photo"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/franco.jpg' ); ?>" loading="lazy" alt="Franco Ruiz sentado en su espacio de trabajo" width="720" height="1160"></div>
      <div>
        <h2>Sobre mí</h2>
        <div class="about-text">
          <p>Nací en Buenos Aires en 1992. Hace más de diez años abracé el coaching como disciplina, con tres bases: el respeto, la transformación humana y el cumplimiento de metas. En ese camino entendí el peso que tiene lo que pensamos y lo que nos decimos a nosotros mismos: las palabras que elegimos terminan definiendo hasta dónde llegamos.</p>
          <p>Acompañé a personas con vivencias y ambiciones muy distintas: deportistas de alto rendimiento, empresarios, políticos y empleados. Hoy elijo trabajar con pocas personas, las que tienen metas grandes y buscan un acompañamiento a la altura de lo que se proponen.</p>
          <p>Mi trabajo consiste en poner en palabras situaciones de alta complejidad. Muchas veces quien llega todavía no sabe bien qué le pasa, y lo ordenamos juntos. Trabajo desde el desprejuicio absoluto.</p>
          <p>Soy coach ontológico, formado tanto en lo teórico como en talleres vivenciales, y me capacité además en programación neurolingüística y comunicación no verbal. Desde hace más de trece años trabajo también en el sector financiero, así que conozco desde adentro los entornos de exigencia y jerarquía.</p>
          <p>También soy artista. La música y el teatro me conectan con la pasión, el disfrute y la creatividad.</p>
        </div>
      </div>
    </div>
  </section>

  <section class="block block-stone" id="para-quien">
    <div class="wrap">
      <h2>Para quién es</h2>
      <ul class="who">
        <li>Líderes, profesionales y emprendedores con impacto, que sienten el peso de la toma constante de decisiones y la exigencia de sostener resultados.</li>
        <li>Personas con alto nivel de responsabilidad, que funcionan bien hacia afuera pero necesitan claridad y orden hacia adentro.</li>
        <li>Jóvenes adultos que están llamados a ocupar lugares importantes, que atraviesan dudas identitarias, presión externa o expectativas del entorno.</li>
      </ul>
    </div>
  </section>


  <section class="method" id="metodologia">
    <div class="wrap">
      <div class="rule"></div>
      <h2>Metodología</h2>
      <p class="big">No uso fórmulas genéricas ni motivación vacía: diseño un proceso personalizado que respete tu ritmo, tu historia y tus objetivos reales.</p>
    </div>
  </section>


  <section class="block" id="como-trabajo">
    <div class="wrap">
      <h2>Cómo trabajo</h2>
      <ol class="steps">
        <li>
          <h3>Entrevista privada</h3>
          <p>Para conocernos, entender tu situación e identificar si este tipo de acompañamiento se adapta a tus necesidades actuales.</p>
        </li>
        <li>
          <h3>Diagnóstico personal</h3>
          <p>Analizamos tu situación actual. Exploramos con profundidad tus objetivos, patrones y puntos ciegos.</p>
        </li>
        <li>
          <h3>Plan de trabajo</h3>
          <p>Definimos un enfoque claro y accionable, adaptado a tu realidad y tu ritmo.</p>
        </li>
        <li>
          <h3>Acompañamiento</h3>
          <p>Sesiones de conversación profunda, presenciales u online, con ajustes y seguimiento para sostener el cambio.</p>
        </li>
      </ol>
    </div>
  </section>


  <section class="block block-stone" id="formulario">
    <div class="wrap split">
      <div>
        <h2>El primer paso es una conversación clara</h2>
        <p class="muted" style="margin-top:1.25rem; max-width:36ch;">Trabajo con pocas personas a la vez y con total confidencialidad, para garantizar profundidad, foco y resultados concretos. Si sentís que es el momento, podemos coordinar una primera reunión.</p>
      </div>

      <div class="form-shell">
        <form id="apply-form" novalidate>
          <div class="progress" aria-live="polite">
            <span id="progress-text">Paso 1 de 3</span>
            <div class="bar"><span id="progress-bar"></span></div>
          </div>

          <div class="step is-on" data-step="1">
            <h3>¿En qué área de tu vida sentís que necesitás mayor claridad ahora mismo?</h3>
            <div class="field">
              <label for="f-claridad">Tu respuesta</label>
              <textarea id="f-claridad" name="claridad" required></textarea>
              <p class="error">Escribí tu respuesta para seguir, aunque sea una línea.</p>
            </div>
          </div>

          <div class="step" data-step="2">
            <h3>Hacia dónde querés ir</h3>
            <div class="field">
              <label for="f-objetivo">¿Qué te gustaría lograr en los próximos 6–12 meses?</label>
              <textarea id="f-objetivo" name="objetivo" required></textarea>
              <p class="error">Completá este campo para seguir.</p>
            </div>
            <div class="field">
              <label for="f-expectativa">¿Qué esperás de una conversación conmigo?</label>
              <textarea id="f-expectativa" name="expectativa" required></textarea>
              <p class="error">Completá este campo para seguir.</p>
            </div>
          </div>

          <div class="step" data-step="3">
            <h3>Tus datos de contacto</h3>
            <div class="three">
              <div class="field">
                <label for="f-nombre">Nombre</label>
                <input id="f-nombre" name="nombre" type="text" autocomplete="given-name" required>
                <p class="error">Falta tu nombre.</p>
              </div>
              <div class="field">
                <label for="f-apellido">Apellido</label>
                <input id="f-apellido" name="apellido" type="text" autocomplete="family-name" required>
                <p class="error">Falta tu apellido.</p>
              </div>
              <div class="field">
                <label for="f-edad">Edad</label>
                <input id="f-edad" name="edad" type="number" min="18" max="99" inputmode="numeric">
              </div>
            </div>
            <div class="two">
              <div class="field">
                <label for="f-email">Correo electrónico</label>
                <input id="f-email" name="email" type="email" autocomplete="email" required>
                <p class="error">Revisá el correo, parece incompleto.</p>
              </div>
              <div class="field">
                <label for="f-tel">Teléfono <span class="hint" id="tel-hint"></span></label>
                <input id="f-tel" name="telefono" type="tel" autocomplete="tel" required>
                <p class="error">Dejá un teléfono para poder escribirte por WhatsApp.</p>
              </div>
            </div>
            <fieldset class="choices">
              <legend class="legend">¿Cómo preferís que te contacte?</legend>
              <div class="row">
                <label><input type="radio" name="contacto" value="WhatsApp" checked> WhatsApp</label>
                <label><input type="radio" name="contacto" value="E-mail"> E-mail</label>
              </div>
            </fieldset>
            <input class="hp" type="text" name="empresa_web" tabindex="-1" autocomplete="off" aria-hidden="true">
          </div>

          <div class="step done" data-step="done" tabindex="-1">
            <h3>Aplicación enviada</h3>
            <p>Gracias por tomarte el tiempo. La leo personalmente y te contacto por el medio que elegiste.</p>
          </div>

          <p class="error" id="send-error" role="alert">No se pudo enviar la aplicación. Revisá tu conexión y volvé a intentar.</p>

          <div class="actions" id="actions">
            <button type="button" class="btn btn-ghost" id="back" hidden>Volver</button>
            <span class="spacer"></span>
            <button type="button" class="btn btn-dark" id="next">Continuar</button>
          </div>
        </form>
      </div>
    </div>
  </section>

</main>

<footer>
  <div class="wrap">
    <span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> Franco Ruiz. Asesor Personal en Desarrollo Humano.</span>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
