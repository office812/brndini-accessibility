<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AB_Site_Audit {
	/**
	 * @return array<string, mixed>
	 */
	public function run() {
		$settings      = AB_Plugin::get_settings();
		$target_url    = home_url( '/' );
		$response      = wp_remote_get(
			$target_url,
			array(
				'timeout'     => 15,
				'redirection' => 5,
				'user-agent'  => 'A11Y Bridge WordPress Audit/0.1.0',
			)
		);
		$issues        = array();
		$signals       = array();
		$notes         = array();
		$html          = '';
		$status_code   = 0;
		$fetched_title = get_bloginfo( 'name' );

		if ( is_wp_error( $response ) ) {
			$issues[] = $this->create_issue(
				'crawl_error',
				'לא הצלחנו למשוך את דף הבית',
				'high',
				1,
				'וורדפרס לא הצליח לטעון את ה-HTML של דף הבית לבדיקת audit.',
				'ללא גישה ל-HTML הראשי אי אפשר לבצע triage אמין.',
				'לוודא שהאתר נגיש מתוך השרת עצמו ושאין חסימה מקומית או WAF.',
				array( 'Operational' )
			);
			$notes[]  = $response->get_error_message();
		} else {
			$status_code = (int) wp_remote_retrieve_response_code( $response );
			$html        = (string) wp_remote_retrieve_body( $response );
		}

		if ( ! empty( $html ) && class_exists( 'DOMDocument' ) ) {
			$document = new DOMDocument();

			libxml_use_internal_errors( true );
			$document->loadHTML( $html );
			libxml_clear_errors();

			$xpath = new DOMXPath( $document );

			$title_nodes = $document->getElementsByTagName( 'title' );
			if ( $title_nodes->length > 0 ) {
				$fetched_title = trim( (string) $title_nodes->item( 0 )->textContent );
			}

			$html_nodes = $document->getElementsByTagName( 'html' );
			$lang_attr  = '';
			if ( $html_nodes->length > 0 ) {
				$lang_attr = (string) $html_nodes->item( 0 )->getAttribute( 'lang' );
			}

			if ( '' === trim( $lang_attr ) ) {
				$issues[] = $this->create_issue(
					'missing-lang',
					'חסר lang במסמך הבית',
					'high',
					1,
					'עמוד הבית אינו מצהיר על שפה ברמת ה-HTML.',
					'קוראי מסך עלולים לבחור הגייה לא נכונה.',
					'להבטיח שוורדפרס מדפיס lang נכון גם כאשר התבנית מותאמת אישית.',
					array( '3.1.1' )
				);
			}

			$main_count = (int) $xpath->query( '//main | //*[@role="main"]' )->length;
			if ( 0 === $main_count ) {
				$issues[] = $this->create_issue(
					'missing-main',
					'חסר main landmark',
					'medium',
					1,
					'לא נמצא main יחיד בתבנית הראשית.',
					'ניווט מהיר בין אזורי תוכן נפגע.',
					'להוסיף main או role="main" בתבנית.',
					array( '1.3.1', '2.4.1' )
				);
			}

			$skip_links = (int) $xpath->query( '//a[contains(@href, "#main-content") or contains(@class, "ab-skip-link")]' )->length;
			if ( 0 === $skip_links ) {
				$issues[] = $this->create_issue(
					'missing-skip-link',
					'לא נמצא skip link',
					'medium',
					1,
					'לא אותר קישור דילוג לתוכן הראשי.',
					'משתמשי מקלדת צריכים לעבור תפריטים לפני ההגעה לתוכן.',
					'להפעיל את רכיב ה-skip link או להוסיף אותו בתבנית.',
					array( '2.4.1' )
				);
			}

			$buttons_without_name = (int) $xpath->query( '//button[not(normalize-space()) and not(@aria-label) and not(@aria-labelledby) and not(@title)]' )->length;
			if ( $buttons_without_name > 0 ) {
				$issues[] = $this->create_issue(
					'nameless-buttons',
					'כפתורים ללא שם נגיש',
					'critical',
					$buttons_without_name,
					'נמצאו כפתורים ריקים ללא שם חלופי.',
					'פעולות לא מזוהות לקוראי מסך.',
					'להוסיף טקסט גלוי, aria-label או aria-labelledby.',
					array( '4.1.2', '2.4.6' )
				);
			}

			$form_elements = $xpath->query( '//input[not(@type="hidden")] | //select | //textarea' );
			$missing_labels = 0;

			if ( $form_elements instanceof DOMNodeList ) {
				foreach ( $form_elements as $element ) {
					$id = '';

					if ( $element instanceof DOMElement ) {
						$id = (string) $element->getAttribute( 'id' );
					}

					$has_programmatic_label = $element instanceof DOMElement && (
						$element->hasAttribute( 'aria-label' ) ||
						$element->hasAttribute( 'aria-labelledby' ) ||
						$element->hasAttribute( 'title' )
					);

					$has_parent_label = false;
					$parent           = $element->parentNode;
					while ( $parent instanceof DOMNode ) {
						if ( $parent instanceof DOMElement && 'label' === strtolower( $parent->tagName ) ) {
							$has_parent_label = true;
							break;
						}
						$parent = $parent->parentNode;
					}

					$has_for_label = false;
					if ( '' !== $id ) {
						$label_query   = sprintf( '//label[@for=%s]', $this->escape_xpath_literal( $id ) );
						$has_for_label = (int) $xpath->query( $label_query )->length > 0;
					}

					if ( ! $has_programmatic_label && ! $has_parent_label && ! $has_for_label ) {
						$missing_labels++;
					}
				}
			}

			if ( $missing_labels > 0 ) {
				$issues[] = $this->create_issue(
					'missing-field-labels',
					'שדות טופס ללא label',
					'critical',
					$missing_labels,
					'יש שדות טופס ללא שיוך label או aria-label.',
					'משתמשים עלולים לא להבין איזה מידע עליהם להזין.',
					'להקשיח קומפוננטות טפסים ולחייב label.',
					array( '1.3.1', '3.3.2', '4.1.2' )
				);
			}

			$generic_links = 0;
			$link_nodes    = $xpath->query( '//a' );
			if ( $link_nodes instanceof DOMNodeList ) {
				foreach ( $link_nodes as $link_node ) {
					$text = trim( strtolower( preg_replace( '/\s+/', ' ', (string) $link_node->textContent ) ) );
					if ( in_array( $text, array( 'click here', 'read more', 'more', 'עוד', 'לחץ כאן', 'לפרטים' ), true ) ) {
						$generic_links++;
					}
				}
			}

			if ( $generic_links > 0 ) {
				$issues[] = $this->create_issue(
					'generic-links',
					'קישורים עם טקסט גנרי',
					'medium',
					$generic_links,
					'נמצאו קישורים שאינם מסבירים את היעד.',
					'רשימת קישורים נעשית פחות מובנת לקוראי מסך.',
					'להחליף לקופי תיאורי ברמת התוכן או הקומפוננטה.',
					array( '2.4.4' )
				);
			}
		}

		$attachments_missing_alt = $this->count_attachments_without_alt();
		if ( $attachments_missing_alt > 0 ) {
			$issues[] = $this->create_issue(
				'media-library-alt',
				'קבצי מדיה ללא alt בספרייה',
				'medium',
				$attachments_missing_alt,
				'נמצאו קבצי מדיה בספריית וורדפרס ללא alt.',
				'תוכן שמוכנס בעתיד עלול להמשיך להגיע ללא טקסט חלופי.',
				'לנקות ספריית מדיה ולהוסיף דרישת alt בתהליך העריכה.',
				array( '1.1.1' )
			);
		}

		if ( empty( $settings['statement_url'] ) ) {
			$issues[] = $this->create_issue(
				'missing-statement',
				'לא הוגדרה הצהרת נגישות',
				'low',
				1,
				'לא נשמר קישור להצהרת נגישות במסך ההגדרות.',
				'ללקוח חסרה שכבת שקיפות ותיעוד.',
				'להוסיף URL לעמוד הצהרת הנגישות.',
				array( 'Operational' )
			);
		}

		$signals[] = array(
			'label' => 'HTTP status',
			'value' => $status_code ? (string) $status_code : 'n/a',
		);
		$signals[] = array(
			'label' => 'Posts',
			'value' => (string) wp_count_posts( 'post' )->publish,
		);
		$signals[] = array(
			'label' => 'Pages',
			'value' => (string) wp_count_posts( 'page' )->publish,
		);
		$signals[] = array(
			'label' => 'Media w/o alt',
			'value' => (string) $attachments_missing_alt,
		);

		$report = array(
			'generated_at' => current_time( 'mysql' ),
			'url'          => $target_url,
			'title'        => $fetched_title,
			'score'        => $this->score_issues( $issues ),
			'summary'      => 'זהו audit תפעולי ראשוני מתוך וורדפרס. הוא טוב ל-triage מהיר ולמכירה ראשונית, אך לא מחליף בדיקות ידניות, ניווט מקלדת או קורא מסך.',
			'highlights'   => $this->build_highlights( $issues, $attachments_missing_alt ),
			'autofixes'    => $this->build_autofixes( $settings, $issues ),
			'next_steps'   => $this->build_next_steps( $issues ),
			'issues'       => $issues,
			'signals'      => $signals,
			'notes'        => $notes,
		);

		AB_Plugin::update_last_audit( $report );

		return $report;
	}

	private function count_attachments_without_alt() {
		$query = new WP_Query(
			array(
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'post_status'    => 'inherit',
				'posts_per_page' => 100,
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'orderby'        => 'date',
				'order'          => 'DESC',
			)
		);

		$count = 0;

		if ( ! empty( $query->posts ) ) {
			foreach ( $query->posts as $attachment_id ) {
				$alt = get_post_meta( (int) $attachment_id, '_wp_attachment_image_alt', true );
				if ( '' === trim( (string) $alt ) ) {
					$count++;
				}
			}
		}

		wp_reset_postdata();

		return $count;
	}

	private function score_issues( array $issues ) {
		$weights = array(
			'critical' => 12,
			'high'     => 8,
			'medium'   => 5,
			'low'      => 2,
		);
		$penalty = 0;

		foreach ( $issues as $issue ) {
			$severity = isset( $issue['severity'] ) ? $issue['severity'] : 'low';
			$count    = isset( $issue['count'] ) ? (int) $issue['count'] : 1;
			$penalty += min( 6, $count ) * ( isset( $weights[ $severity ] ) ? $weights[ $severity ] : 2 );
		}

		return max( 16, 100 - $penalty );
	}

	private function build_highlights( array $issues, $attachments_missing_alt ) {
		$highlights = array();

		if ( empty( $issues ) ) {
			$highlights[] = 'לא נמצאו ממצאים בסיסיים בבדיקת הבית ובספריית המדיה.';
			$highlights[] = 'השלב הבא הוא לעבור ידנית על תהליכים, טפסים ופוקוס.';
			return $highlights;
		}

		$critical = $this->find_issue_by_severity( $issues, 'critical' );
		$high     = $this->find_issue_by_severity( $issues, 'high' );

		$highlights[] = sprintf( 'הדוח מצא %d קבוצות ממצאים שדורשות triage.', count( $issues ) );

		if ( ! empty( $critical ) && ! empty( $critical['title'] ) ) {
			$highlights[] = 'החסם הבולט ביותר כרגע: ' . $critical['title'] . '.';
		}

		if ( ! empty( $high ) && ! empty( $high['title'] ) ) {
			$highlights[] = 'יש גם בעיה ברמת חומרה גבוהה: ' . $high['title'] . '.';
		}

		if ( $attachments_missing_alt > 0 ) {
			$highlights[] = 'ספריית המדיה עצמה צריכה ניקוי כדי למנוע חזרה של בעיות alt.';
		}

		return $highlights;
	}

	private function build_autofixes( array $settings, array $issues ) {
		$autofixes = array();

		if ( ! empty( $settings['auto_skip_link'] ) ) {
			$autofixes[] = 'התוסף יכול להזריק skip link ולהצמיד אותו לאזור content/main קיים.';
		}

		if ( ! empty( $settings['enhance_focus_styles'] ) ) {
			$autofixes[] = 'התוסף מחיל focus ring ברור יותר על רכיבים אינטראקטיביים בלי לשנות את התוכן.';
		}

		if ( ! empty( $settings['normalize_lang'] ) ) {
			$autofixes[] = 'התוסף מחזק את הדפסת lang ברמת ה-template כאשר אפשר.';
		}

		foreach ( $issues as $issue ) {
			if ( isset( $issue['id'] ) && 'missing-statement' === $issue['id'] ) {
				$autofixes[] = 'להוסיף URL להצהרת נגישות מתוך מסך ההגדרות.';
			}
		}

		if ( empty( $autofixes ) ) {
			$autofixes[] = 'לא זוהה auto-fix בטוח נוסף. מכאן ממשיכים לקוד התבנית ולתוכן.';
		}

		return array_values( array_unique( $autofixes ) );
	}

	private function build_next_steps( array $issues ) {
		$next_steps = array(
			'להריץ בדיקת מקלדת מלאה על התפריט, הטפסים והפוטר.',
			'לעבור עם קורא מסך על עמוד הבית ועל תהליך עסקי מרכזי אחד.',
		);

		foreach ( $issues as $issue ) {
			if ( empty( $issue['id'] ) ) {
				continue;
			}

			if ( 'missing-field-labels' === $issue['id'] ) {
				$next_steps[] = 'להקשיח את רכיבי הטופס כך שלא ניתן לייצר field ללא label.';
			}

			if ( 'media-library-alt' === $issue['id'] ) {
				$next_steps[] = 'לנקות את ספריית המדיה ולהגדיר תהליך editorial לשדות alt.';
			}

			if ( 'nameless-buttons' === $issue['id'] ) {
				$next_steps[] = 'לעבור על כפתורי אייקון בכותרת, ב-widgets וב-popupים.';
			}
		}

		return array_values( array_unique( $next_steps ) );
	}

	private function find_issue_by_severity( array $issues, $severity ) {
		foreach ( $issues as $issue ) {
			if ( isset( $issue['severity'] ) && $severity === $issue['severity'] ) {
				return $issue;
			}
		}

		return array();
	}

	private function create_issue( $id, $title, $severity, $count, $description, $impact, $action, array $wcag ) {
		return array(
			'id'          => $id,
			'title'       => $title,
			'severity'    => $severity,
			'count'       => (int) $count,
			'description' => $description,
			'impact'      => $impact,
			'action'      => $action,
			'wcag'        => $wcag,
		);
	}

	private function escape_xpath_literal( $value ) {
		if ( false === strpos( $value, '"' ) ) {
			return '"' . $value . '"';
		}

		if ( false === strpos( $value, "'" ) ) {
			return "'" . $value . "'";
		}

		$parts = explode( '"', $value );
		$escaped = array();

		foreach ( $parts as $index => $part ) {
			if ( '' !== $part ) {
				$escaped[] = '"' . $part . '"';
			}

			if ( $index < count( $parts ) - 1 ) {
				$escaped[] = "'\"'";
			}
		}

		return 'concat(' . implode( ', ', $escaped ) . ')';
	}
}
