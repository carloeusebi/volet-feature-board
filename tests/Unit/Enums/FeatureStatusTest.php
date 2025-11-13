<?php

use Mydnic\VoletFeatureBoard\Enums\FeatureStatus;

test('feature status enum has correct cases', function () {
    expect(FeatureStatus::PENDING->value)->toBe('pending');
    expect(FeatureStatus::APPROVED->value)->toBe('approved');
    expect(FeatureStatus::REJECTED->value)->toBe('rejected');
    expect(FeatureStatus::COMPLETED->value)->toBe('completed');
});

test('feature status enum returns correct labels', function () {
    expect(FeatureStatus::PENDING->getLabel())->toBe('Pending');
    expect(FeatureStatus::APPROVED->getLabel())->toBe('Approved');
    expect(FeatureStatus::REJECTED->getLabel())->toBe('Rejected');
    expect(FeatureStatus::COMPLETED->getLabel())->toBe('Completed');
});
