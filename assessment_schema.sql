-- Run this once to set up the assessment tables

-- Stores one assessment record per (internship + assessor) pair
CREATE TABLE IF NOT EXISTS assessments (
    assessment_id   INT AUTO_INCREMENT PRIMARY KEY,
    internship_id   INT         NOT NULL,
    assessor_id     INT         NOT NULL,
    role            VARCHAR(20) NOT NULL COMMENT 'lecturer or supervisor',
    total_score     DECIMAL(5,2) DEFAULT 0.00,
    submitted_at    DATETIME    DEFAULT NULL,
    created_at      DATETIME    DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME    DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- Prevent duplicate assessments for the same internship+assessor
    UNIQUE KEY uq_assessment (internship_id, assessor_id),

    FOREIGN KEY (internship_id) REFERENCES internships(internship_id) ON DELETE CASCADE,
    FOREIGN KEY (assessor_id)   REFERENCES users(user_id)             ON DELETE CASCADE
);

-- Stores one row per criteria component per assessment
CREATE TABLE IF NOT EXISTS assessment_marks (
    mark_id         INT AUTO_INCREMENT PRIMARY KEY,
    assessment_id   INT            NOT NULL,
    component       VARCHAR(255)   NOT NULL COMMENT 'e.g. Technical Skills',
    weight          DECIMAL(5,2)   NOT NULL COMMENT 'percentage weight, e.g. 20.00',
    score           TINYINT        NOT NULL COMMENT '1 to 5',
    weighted_score  DECIMAL(6,2)   NOT NULL COMMENT 'weight * score / 5 * 100',
    notes           TEXT           DEFAULT NULL,
    created_at      DATETIME       DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    -- One row per component per assessment; re-saving overwrites
    UNIQUE KEY uq_mark (assessment_id, component),

    FOREIGN KEY (assessment_id) REFERENCES assessments(assessment_id) ON DELETE CASCADE
);
