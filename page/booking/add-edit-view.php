<?php
/*
 * DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
 *
 * Copyright 2011 Oracle and/or its affiliates. All rights reserved.
 *
 * Oracle and Java are registered trademarks of Oracle and/or its affiliates.
 * Other names may be trademarks of their respective owners.
 *
 * The contents of this file are subject to the terms of either the GNU
 * General Public License Version 2 only ("GPL") or the Common
 * Development and Distribution License("CDDL") (collectively, the
 * "License"). You may not use this file except in compliance with the
 * License. You can obtain a copy of the License at
 * http://www.netbeans.org/cddl-gplv2.html
 * or nbbuild/licenses/CDDL-GPL-2-CP. See the License for the
 * specific language governing permissions and limitations under the
 * License.  When distributing the software, include this License Header
 * Notice in each file and include the License file at
 * nbbuild/licenses/CDDL-GPL-2-CP.  Oracle designates this
 * particular file as subject to the "Classpath" exception as provided
 * by Oracle in the GPL Version 2 section of the License file that
 * accompanied this code. If applicable, add the following below the
 * License Header, with the fields enclosed by brackets [] replaced by
 * your own identifying information:
 * "Portions Copyrighted [year] [name of copyright owner]"
 *
 * If you wish your version of this file to be governed by only the CDDL
 * or only the GPL Version 2, indicate your decision by adding
 * "[Contributor] elects to include this software in this distribution
 * under the [CDDL or GPL Version 2] license." If you do not indicate a
 * single choice of license, a recipient has the option to distribute
 * your version of this file under either the CDDL, the GPL Version 2 or
 * to extend the choice of license to its licensees as provided above.
 * However, if you add GPL Version 2 code and therefore, elected the GPL
 * Version 2 license, then the option applies only if the new code is
 * made subject to such option by the copyright holder.
 *
 * Contributor(s):
 *
 * Portions Copyrighted 2011 Sun Microsystems, Inc.
 */

//~ Template for add-edit.php
// variables:
//  $errors - validation errors
//  $todo - submitted TODO
//  $edit - true for EDIT, false for ADD

function error_field($title, array $errors) {
    foreach ($errors as $error) {
        /* @var $error Error */
        if ($error->getSource() == $title) {
            return ' error-field';
        }
    }
    return '';
}

/* @var $booking Booking */
?>

<h1>
    <?php if ($edit): ?>
        Edit&nbsp;
    <?php else: ?>
        Add&nbsp; 
    <?php endif; ?>
        new Booking
</h1>

<?php if (!empty($errors)): ?>
    <ul class="errors">
        <?php foreach ($errors as $error): ?>
            <?php /* @var $error Error */ ?>
            <li><?php echo $error->getMessage(); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<form action="#" method="post">
    <fieldset>
        <div class="field">
            <label>Flight name:</label>
            <select name="booking[flight_name]">
            <?php foreach ($flightNames as $flightName): ?>
                <option value="<?php echo $flightName; ?>"
                    <?php if ($booking->getFlightName() == $flightName): ?>
                            selected="selected"
                        <?php endif; ?>
                        ><?php echo Utils::escape($flightName); ?></option>
            <?php endforeach; ?>
            </select>
<!--            <select name="booking[flight_name]">
                <option value="<?php// echo $i; ?>">Helicopter sightseeing</option>
                <option value="Glider">Glider</option>
                <option value="Tramping excursion">Tramping excursion</option>
                <option value="Heliskiing">Heliskiing</option>
            </select>-->
<!--            <input type="text" name="booking[flight_name]" value="<?php //echo Utils::escape($booking->getFlightName()); ?>"
                   class="text<?php //echo error_field('flight_name', $errors); ?>"/>-->
        </div>
        <div class="field">
            <label>Flight date:</label>
            <input id="flight_date" type="text" name="booking[flight_date]" value="<?php echo Utils::escape($booking->getFlightDate()->format('Y-n-j')); ?>"
                   class="text datepicker<?php echo error_field('flight_date', $errors); ?>" />
        </div>
        <div class="wrapper">
        <!--<input type="submit" name="cancel" value="CANCEL" class="submit" />-->
            <input type="submit" name="save" value="<?php echo $edit ? 'EDIT' : 'ADD'; ?>" class="submit" />
        </div>
    </fieldset>
</form>
